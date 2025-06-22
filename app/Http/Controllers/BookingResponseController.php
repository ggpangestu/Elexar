<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\UserResponseStatus;
use Illuminate\Http\Request;
use App\Notifications\BookingRescheduleAcceptedNotification;

class BookingResponseController extends Controller
{
    public function show($token)
    {
        $bookings = Booking::where('reschedule_token', $token)
                        ->where('status', BookingStatus::Rescheduled)
                        ->firstOrFail();

        if (
            now()->greaterThan($bookings->reschedule_expires_at)
            || $bookings->user_response_status !== UserResponseStatus::Pending
        ) {
            abort(403, 'Link ini sudah tidak berlaku.');
        }

        return view('admin.bookings.reschedule', compact('bookings'));
    }

    public function respond(Request $request, $token)
    {
        \Log::info('Masuk ke respond()', $request->all());

        $request->validate([
            'action' => 'required|in:accept,reject',
        ]);

        $booking = Booking::where('reschedule_token', $token)
                        ->where('status', BookingStatus::Rescheduled)
                        ->firstOrFail();

        \Log::info('Data booking ditemukan', [
            'status' => $booking->status,
            'expires_at' => $booking->reschedule_expires_at,
            'user_response_status' => $booking->user_response_status
        ]);

        if (now()->greaterThan($booking->reschedule_expires_at) || $booking->user_response_status !== UserResponseStatus::Pending) {
            \Log::warning('Link expired atau user sudah merespon');
            return redirect()->route('reschedule.show', ['token' => $token])
                ->with('error', 'Link sudah tidak berlaku.');
        }

        $booking->user_responded_at = now();
        $booking->user_response_status = $request->action === 'accept'
            ? UserResponseStatus::Accepted
            : UserResponseStatus::Rejected;

        if ($request->action === 'accept') {
            $booking->meeting_link = $booking->pending_meeting_link;
            $booking->pending_meeting_link = null;
            $booking->status = BookingStatus::Accepted;

            // 🔔 Kirim email konfirmasi reschedule
            $booking->user->notify(new BookingRescheduleAcceptedNotification($booking));
        } else {
            $booking->status = BookingStatus::Rejected;
            $booking->pending_meeting_link = null;
        }

        $booking->save();

        \Log::info('Reschedule disimpan', [
            'action' => $request->action,
            'new_status' => $booking->status,
            'meeting_link' => $booking->meeting_link,
        ]);

        return view('admin.bookings.resultReschedule', [
                'status' => $request->action,
                'message' => $request->action === 'accept'
                    ? 'Terima kasih! Anda telah menerima jadwal baru.'
                    : 'Anda telah menolak penjadwalan ulang.'
        ]);
    }
}
