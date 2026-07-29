<?php

namespace App\Exports;

use App\Models\DailyStoreSale;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreSalesReportExport implements FromCollection, WithHeadings
{
    protected $search;
     protected $storeType;
    protected $startDate;
    protected $endDate;
   


    public function __construct($search,$storeType ,$startDate, $endDate)
    {
        $this->search = $search;
        $this->storeType = $storeType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        
    }

 public function collection()
{
    $query = DailyStoreSale::join(
            'store_masters as sm',
            'daily_store_sales.n_store_id',
            '=',
            'sm.n_store_id'
        )
        ->select(
            DB::raw("DATE_FORMAT(daily_store_sales.d_date, '%d-%m-%Y') as d_date"),
            'sm.c_store_code',
            'sm.c_store_name',
            DB::raw('(daily_store_sales.n_sold_price * daily_store_sales.n_quantity) as total_sales'),
            DB::raw('(daily_store_sales.n_buying_rate * daily_store_sales.n_quantity) as total_purchase'),
            DB::raw('((daily_store_sales.n_sold_price - daily_store_sales.n_buying_rate) * daily_store_sales.n_quantity) as total_profit')
        );

    // Search
    if ($this->search) {
        $query->where(function ($q) {
            $q->where('sm.c_store_name', 'like', "%{$this->search}%")
              ->orWhere('sm.c_store_code', 'like', "%{$this->search}%");
        });
    }

    // Store Type Filter
    if ($this->storeType == 'centreal') {
        $query->where('sm.c_store_email', 'like', '%@centreal%');
    }

    if ($this->storeType == 'vanitham') {
        $query->where('sm.c_store_email', 'like', '%@vanitham%');
    }

    // Date Filter
    if ($this->startDate && $this->endDate) {
        $query->whereRaw(
            'DATE(daily_store_sales.d_date) BETWEEN ? AND ?',
            [$this->startDate, $this->endDate]
        );
    } elseif ($this->startDate) {
        $query->whereRaw(
            'DATE(daily_store_sales.d_date) >= ?',
            [$this->startDate]
        );
    } elseif ($this->endDate) {
        $query->whereRaw(
            'DATE(daily_store_sales.d_date) <= ?',
            [$this->endDate]
        );
    }

    return $query
        ->orderBy('daily_store_sales.d_date', 'desc')
        ->get();
}
 public function headings(): array
    {
        return [
            'Date',
            'Store Code',
            'Store Name',
            'Total Sales',
            'Total Purchase',
            'Total Profit',
        ];
    }

}