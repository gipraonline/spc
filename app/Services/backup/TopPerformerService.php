<?php

namespace App\Services;

use App\Models\EmployeeIncentive;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopPerformerService
{
    /**
     * Get top performer by designation.
     */
    public function getTopPerformer(string $designation,string $emailDomain)
    {

    // $date = Carbon::createFromFormat('d/m/Y', '19/06/2026')->format('Y-m-d');
        return EmployeeIncentive::select(
                'employee_masters.n_employee_id',
                'employee_masters.c_employee_name',
                'store_masters.c_store_name',
                DB::raw('SUM(employee_incentives.n_incentive_amount) as total_incentive')
            )
            ->join(
                'employee_masters',
                'employee_incentives.n_employee_id',
                '=',
                'employee_masters.n_employee_id'
            )
            ->join(
                'designation_masters',
                'employee_masters.n_designation_id',
                '=',
                'designation_masters.n_designation_id'
            )
            ->join(
                'store_masters',
                'employee_masters.n_store_id',
                '=',
                'store_masters.n_store_id'
            )
            ->whereDate('employee_incentives.d_date', Carbon::yesterday())
            ->where('designation_masters.c_designation', $designation)
            ->where('store_masters.c_store_email', 'like', "%{$emailDomain}%")
            ->groupBy(
                'employee_masters.n_employee_id',
                'employee_masters.c_employee_name',
                'store_masters.c_store_name'
            )
            ->orderByDesc('total_incentive')
            ->first();
    }

    /**
     * Get top cluster performer.
     */
    public function getTopCluster(string $emailDomain)
    {
        return EmployeeIncentive::select(
                'cluster_managers.n_employee_id',
                'cluster_managers.c_employee_name',
                DB::raw('SUM(employee_incentives.n_incentive_amount) as total_incentive')
            )
            ->join(
                'employee_masters',
                'employee_incentives.n_employee_id',
                '=',
                'employee_masters.n_employee_id'
            )
            ->join(
                'store_masters',
                'employee_masters.n_store_id',
                '=',
                'store_masters.n_store_id'
            )
            ->join(
                'store_clusters',
                'store_masters.n_store_id',
                '=',
                'store_clusters.n_store_id'
            )
            ->join(
                'employee_masters as cluster_managers',
                'store_clusters.n_employee_id',
                '=',
                'cluster_managers.n_employee_id'
            )
            ->whereDate('employee_incentives.d_date', Carbon::yesterday())
            ->where('store_masters.c_store_email', 'like', "%{$emailDomain}%")
            ->groupBy(
                'cluster_managers.n_employee_id',
                'cluster_managers.c_employee_name'
            )
            ->orderByDesc('total_incentive')
            ->first();
    }

    /**
     * Get all top performers.
     */
    public function getAllTopPerformers($emailDomain)
    {
        return [
            'topCA'      => $this->getTopPerformer('C&A',$emailDomain),
            'topCSA'     => $this->getTopPerformer('CSA',$emailDomain),
            'topSM'      => $this->getTopPerformer('SM',$emailDomain),
            'topCluster' => $this->getTopCluster($emailDomain),
        ];
    }
}
