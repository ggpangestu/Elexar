<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\UserResponseStatus;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingAcceptedNotification;
use App\Notifications\BookingRejectedNotification;
use App\Notifications\BookingRescheduledNotification;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Booking::with('user')->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $bookings = Booking::with('user')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'booking_date_time' => 'required|date|after:today',
            'category' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'booking_date_time' => $request->booking_date_time,
            'category' => $request->category,
            'notes' => $request->notes,
            'status' => BookingStatus::Pending,
            'user_response_status' => UserResponseStatus::Pending,
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dikirim. Admin akan menghubungi kamu segera dan check email secara berkala.');
    }

    public function accept(Request $request, Booking $booking)
    {
        $request->validate([
            'meeting_link' => 'required|url',
            'meeting_link_note' => 'nullable|string|max:255',
        ]);

        $booking->update([
            'status' => BookingStatus::Accepted,
            'meeting_link' => $request->meeting_link,
            'meeting_link_note' => $request->meeting_link_note,
        ]);

         // Kirim email ke user
        $booking->user->notify(new BookingAcceptedNotification($booking));

        return redirect()->back()->with('success', 'Booking diterima dan link meeting dikirim.');
    }

    public function reject(Booking $booking)
    {
        $booking->update(['status' => BookingStatus::Rejected]);

        $booking->user->notify(new BookingRejectedNotification($booking));
        return redirect()->back()->with('success', 'Booking berhasil ditolak dan notifikasi telah dikirim.');

    }

    public function reschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date_time' => 'required|date|after:now',
            'reschedule_reason' => 'required|string|max:255',
            'pending_meeting_link' => 'required|url',
        ]);

        $newTime = $request->booking_date_time;

        $booking->update([
            'booking_date_time' => $newTime,
            'reschedule_reason' => $request->reschedule_reason,
            'pending_meeting_link' => $request->pending_meeting_link,
            'status' => BookingStatus::Rescheduled,
            'reschedule_token' => Str::uuid(),
            'reschedule_expires_at' => Carbon::parse($newTime)->subMinutes(5),
            'user_response_status' => UserResponseStatus::Pending,
            'user_responded_at' => null,
            'meeting_link' => null,
        ]);

        $booking->user->notify(new BookingRescheduledNotification($booking));

        return back()->with('success', 'Booking berhasil dijadwalkan ulang.');
    }

    public function exportPdf(Request $request)
    {
        $chartBase64 = $request->chart;
        $timeRange = $request->input('time_range', 'monthly');
        $dataType = $request->input('data_type', 'users');

        // Label untuk ditampilkan di PDF
        $rangeLabel = match ($timeRange) {
            'daily' => 'Harian',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => ''
        };

        // Rentang waktu berdasarkan filter
        [$from, $to] = match ($timeRange) {
            'daily' => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
        };

        // === USER EXPORT ===
        if ($dataType === 'users') {
            $users = User::where('role', 'user')
                ->whereBetween('created_at', [$from, $to])
                ->latest()
                ->get();

            return Pdf::loadView('admin.bookings.report-pdf', [
                'users' => $users,
                'chartBase64' => $chartBase64,
                'rangeLabel' => $rangeLabel,
                'dataType' => 'users',
            ])->setPaper('a4', 'portrait')
            ->download('laporan-user-elexar.pdf');
        }

        // === BOOKING EXPORT ===
        $bookings = Booking::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        return Pdf::loadView('admin.bookings.report-pdf', [
            'bookings' => $bookings,
            'chartBase64' => $chartBase64,
            'rangeLabel' => $rangeLabel,
            'dataType' => 'bookings',
        ])->setPaper('a4', 'portrait')
        ->download('laporan-booking-elexar.pdf');
    }
}
