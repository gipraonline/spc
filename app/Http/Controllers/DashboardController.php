<?php

namespace App\Http\Controllers;

use App\Models\FieldLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get authenticated admin/user
        $user = Auth::user();

        // Get today's field log for the logged-in user
         $todayLog = $user->fieldLogs()
            ->whereDate('work_date', today())
            ->first();

        // Default values
        $checkInTime = null;
        $checkOutTime = null;
        $totalWorkingMinutes = 0;
        $workStatus = 'Not Checked In';
        $workStatusSubtext = 'No Attendance';

        if ($todayLog) {

            // Check-in
            if ($todayLog->check_in_time) {
                $checkInTime = $todayLog->check_in_time;
            }

            // Check-out
            if ($todayLog->check_out_time) {
                $checkOutTime = $todayLog->check_out_time;
            }

            // Work status
            if ($todayLog->check_out_time) {

                $workStatus = 'Checked Out';
                $workStatusSubtext = 'Work Completed';

            } elseif ($todayLog->check_in_time) {

                $workStatus = 'Checked In';
                $workStatusSubtext = 'Currently Working';

            }

            // Calculate total working time
            if ($todayLog->check_in_time && $todayLog->check_out_time) {

                $totalWorkingMinutes = $todayLog->check_in_time
                    ->diffInMinutes($todayLog->check_out_time);
            }
        }

        // Convert minutes to HH:MM
        $hours = floor($totalWorkingMinutes / 60);
        $minutes = $totalWorkingMinutes % 60;

        $totalWorkingHours = sprintf(
            '%02d:%02d',
            $hours,
            $minutes
        );

        return view('dashboard', compact(
            'user',
            'todayLog',
            'checkInTime',
            'checkOutTime',
            'totalWorkingHours',
            'workStatus',
            'workStatusSubtext'
        ));
    }
}