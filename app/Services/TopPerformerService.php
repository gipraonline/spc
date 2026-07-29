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
                'employee_masters.c_username',
                'employee_masters.profile_path',
                'store_masters.c_store_name',
                'designation_masters.c_designation as c_designation_name',
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
                'employee_masters.c_username',
                'employee_masters.profile_path',
                'store_masters.c_store_name',
                 'designation_masters.c_designation'
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
            'em.n_employee_id',
            'em.c_employee_name',
            'em.c_username',
            'em.profile_path',
            'dm.c_designation as c_designation_name',
            DB::raw('SUM(ei.n_incentive_amount) as total_incentive')
        )
        ->from('employee_incentives as ei')
        ->join(
            'employee_masters as em',
            'ei.n_employee_id',
            '=',
            'em.n_employee_id'
        )
        ->join(
        'designation_masters as dm',     
        'em.n_designation_id',
        '=',
        'dm.n_designation_id'
    )
        ->where('em.c_status', 'Y')
        ->whereDate('ei.d_date', Carbon::yesterday())
        ->whereExists(function ($q) use ($emailDomain) {
            $q->select(DB::raw(1))
                ->from('store_clusters as sc')
                ->join(
                    'store_masters as sm',
                    'sc.n_store_id',
                    '=',
                    'sm.n_store_id'
                )
                ->whereColumn(
                    'sc.n_employee_id',
                    'em.n_employee_id'
                )
                ->where(
                    'sm.c_store_email',
                    'like',
                    "%{$emailDomain}%"
                );
        })
        ->groupBy(
            'em.n_employee_id',
            'em.c_employee_name',
            'em.c_username',
            'em.profile_path',
            'dm.c_designation'
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
