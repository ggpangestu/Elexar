<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Booking;
use App\Enums\BookingStatus;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::where('role', User::ROLE_USER)->count();
        $adminCount = User::where('role', User::ROLE_ADMIN)->count();
        $bookingCount = Booking::count();
        $bookingAccepted = Booking::where('status', BookingStatus::Accepted)->count();
        $bookingRejected = Booking::where('status', BookingStatus::Rejected)->count();
        $bookingPending = Booking::where('status', BookingStatus::Pending)->count();
        $latestBookings = Booking::whereIn('status', ['pending', 'accepted'])
            ->latest()
            ->take(5)
            ->get();
        $upcomingBookings = Booking::where('booking_date_time', '>', now())
            ->where('status', BookingStatus::Accepted) // ✅ Tambahkan filter status
            ->orderBy('booking_date_time')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'adminCount',
            'bookingCount',
            'bookingAccepted',
            'bookingRejected',
            'bookingPending',
            'latestBookings',
            'upcomingBookings'
        ));
    }

    public function chartData(Request $request)
    {
        $range = $request->query('range', 'monthly');
        $labels = [];
        $data = [];

        if ($range === 'daily') {
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $labels[] = $day->format('d M');
                $count = User::where('role', 'user')
                    ->whereDate('created_at', $day->toDateString())
                    ->count();
                $data[] = $count;
            }
        } elseif ($range === 'monthly') {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('M');
                $count = User::where('role', 'user')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
                $data[] = $count;
            }
        } elseif ($range === 'yearly') {
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i);
                $labels[] = $year->format('Y');
                $count = User::where('role', 'user')
                    ->whereYear('created_at', $year->year)
                    ->count();
                $data[] = $count;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    public function chartBooking(Request $request)
    {
        $range = $request->query('range', 'monthly');
        $labels = [];
        $accepted = [];
        $rejected = [];
        $pending = [];

        if ($range === 'daily') {
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $labels[] = $day->format('d M');

                $accepted[] = Booking::whereDate('created_at', $day->toDateString())
                    ->where('status', BookingStatus::Accepted)->count();

                $rejected[] = Booking::whereDate('created_at', $day->toDateString())
                    ->where('status', BookingStatus::Rejected)->count();

                $pending[] = Booking::whereDate('created_at', $day->toDateString())
                    ->where('status', BookingStatus::Pending)->count();
            }
        } elseif ($range === 'monthly') {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('M');

                $accepted[] = Booking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', BookingStatus::Accepted)->count();

                $rejected[] = Booking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', BookingStatus::Rejected)->count();

                $pending[] = Booking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', BookingStatus::Pending)->count();
            }
        } elseif ($range === 'yearly') {
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i);
                $labels[] = $year->format('Y');

                $accepted[] = Booking::whereYear('created_at', $year->year)
                    ->where('status', BookingStatus::Accepted)->count();

                $rejected[] = Booking::whereYear('created_at', $year->year)
                    ->where('status', BookingStatus::Rejected)->count();

                $pending[] = Booking::whereYear('created_at', $year->year)
                    ->where('status', BookingStatus::Pending)->count();
            }
        }

        return response()->json([
            'labels' => $labels,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'pending' => $pending
        ]);
    }

    public function chartBookingStacked(Request $request)
    {
        $range = $request->query('range', 'monthly');
        $labels = [];
        $accepted = [];
        $rejected = [];
        $pending = [];

        if ($range === 'monthly') {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('M');

                $accepted[] = Booking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', BookingStatus::Accepted)
                    ->count();

                $rejected[] = Booking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', BookingStatus::Rejected)
                    ->count();

                $pending[] = Booking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', BookingStatus::Pending)
                    ->count();
            }
        }

        return response()->json([
            'labels' => $labels,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'pending' => $pending,
        ]);
    }
}
