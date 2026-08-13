<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        // Authenticated user
        $user = Auth::user();

        // Attendance
        $attendance = $dashboardService->getAttendance($user);

        // Dashboard summary
        $summary = $dashboardService->getSummary($user);

        return view('dashboard', [
            'user' => $user,

            // Attendance
            'todayLog' => $attendance['todayLog'],
            'checkInTime' => $attendance['checkInTime'],
            'checkOutTime' => $attendance['checkOutTime'],
            'totalWorkingHours' => $attendance['totalWorkingHours'],
            'workStatus' => $attendance['workStatus'],
            'workStatusSubtext' => $attendance['workStatusSubtext'],

            // Summary cards
            'summary' => $summary,
        ]);
    }
}
