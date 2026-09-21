<?php

namespace App\Services;

use App\Models\SalesOrder;

class DashboardService
{
    /**
     * Get today's attendance information.
     */
    public function getAttendance($user): array
    {
        $todayLog = $user->fieldLogs()
            ->whereDate('work_date', today())
            ->first();

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

            // Calculate working time
            if (
                $todayLog->check_in_time &&
                $todayLog->check_out_time
            ) {
                $totalWorkingMinutes =
                    $todayLog->check_in_time
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

        return [
            'todayLog' => $todayLog,
            'checkInTime' => $checkInTime,
            'checkOutTime' => $checkOutTime,
            'totalWorkingHours' => $totalWorkingHours,
            'workStatus' => $workStatus,
            'workStatusSubtext' => $workStatusSubtext,
        ];
    }

    /**
     * Get today's dashboard summary.
     */
    public function getSummary($user): array
    {
        $salesQuery = SalesOrder::whereDate('d_date', today())
            ->whereNull('deleted_at');

        /*
        |--------------------------------------------------------------------------
        | FCA - only their own sales
        |--------------------------------------------------------------------------
        */

        if ($user->roles()->where('identifier', 'FCA')->exists()) {

            $salesQuery->where(
                'farm_care_advisor_id',
                $user->n_employee_id
            );
        }

        $ordersTaken = (clone $salesQuery)->count();

        $ordersCompleted = (clone $salesQuery)
            ->where('delivery_status', 'Completed')
            ->count();

        $pendingOrders = (clone $salesQuery)
            ->where(function ($query) {
                $query->whereNull('delivery_status')
                    ->orWhere('delivery_status', '!=', 'Completed');
            })
            ->count();

        return [
            'ordersTaken' => $ordersTaken,
            'ordersCompleted' => $ordersCompleted,
            'pendingOrders' => $pendingOrders,

            // Add actual queries later
            'customersVisited' => 0,
            'reportsSubmitted' => 0,
        ];
    }
}
