<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Data dummy
        $userStats = [
            'daily' => 5,
            'monthly' => 150,
            'yearly' => 1800,
        ];

        $meetingStats = [
            'daily' => 2,
            'monthly' => 50,
            'yearly' => 600,
        ];

        return view('admin.dashboard', compact('userStats', 'meetingStats'));
    }
}
