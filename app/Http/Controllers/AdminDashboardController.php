<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::where('role', User::ROLE_USER)->count();
        $adminCount = User::where('role', User::ROLE_ADMIN)->count();

        return view('admin.dashboard', compact('userCount', 'adminCount'));
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
}
