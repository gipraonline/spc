<?php

namespace App\Exports;

use App\Models\DailyStoreSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class OperationStoreReportExport implements FromCollection, WithHeadings,  WithMapping
{
    protected $request;
    private $serial = 1;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $emailMap = [
            '3383'   => '@centrealbazaar',
            '100907' => '@vanitham',
        ];

        $employees = DB::table('employee_masters')
            ->where('n_designation_id',5)
            ->where('c_status','Y')
            ->get();

        $incentiveSubQuery = DB::table('employee_incentives')
            ->select(
                'n_slno',
                DB::raw('SUM(n_incentive_amount) as total_incentive')
            )
            ->groupBy('n_slno');

        $query = DB::table('daily_store_sales as ds')
            ->join('store_masters as sm','ds.n_store_id','=','sm.n_store_id')
            ->leftJoinSub($incentiveSubQuery,'ei',function($join){
                $join->on('ds.n_slno','=','ei.n_slno');
            })
            ->select(
                'ds.d_date',
                'sm.c_store_code',
                'sm.c_store_name',
                DB::raw('SUM(ds.n_sold_price * ds.n_quantity) as sale_amount'),
                DB::raw('SUM(IFNULL(ei.total_incentive,0)) as incentive_amount')
            );

        if (
            $this->request->filled('operation_manager') &&
            $this->request->operation_manager != 'all'
        ) {
            $employee = $employees->firstWhere(
                'n_employee_id',
                $this->request->operation_manager
            );

            if ($employee && isset($emailMap[$employee->c_employee_code])) {
                $query->where(
                    'sm.c_store_email',
                    'like',
                    '%' . $emailMap[$employee->c_employee_code] . '%'
                );
            }
        }

        if ($this->request->filled('start_date')) {
            $query->whereDate(
                'ds.d_date',
                '>=',
                $this->request->start_date
            );
        }

        if ($this->request->filled('end_date')) {
            $query->whereDate(
                'ds.d_date',
                '<=',
                $this->request->end_date
            );
        }

        return $query
            ->groupBy(
                'ds.d_date',
                'sm.n_store_id',
                'sm.c_store_code',
                'sm.c_store_name'
            )
            ->orderBy('ds.d_date')
            ->orderBy('sm.c_store_code')
            ->get();
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Date',
            'Store Code',
            'Store Name',
            'Sales Amount',
            'Incentive Amount',
        ];
    }
     public function map($row): array
    {
        return [
            $this->serial++,
             Carbon::parse($row->d_date)->format('d-m-Y'),
            $row->c_store_code,
            $row->c_store_name,
            $row->sale_amount,
            $row->incentive_amount,
        ];
    }
}
