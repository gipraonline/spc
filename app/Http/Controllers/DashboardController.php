<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeMaster;
use App\Models\StoreMaster;
use App\Models\EmployeeSalesDraft;
use App\Models\EmployeeIncentive;
use App\Models\DesignationMaster;
use App\Models\DailyStoreSale;
use App\Models\ProductMaster;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesViewReportExport;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\StoreSalesReportExport;
use App\Services\TopPerformerService;
use Illuminate\Support\Facades\Auth;


use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $topPerformerService;

    public function __construct(TopPerformerService $topPerformerService)
    {
        $this->topPerformerService = $topPerformerService;
    }
    public function test()
    {
        return view('test');
    }

   public function index()
{

// ---------------------------------------------------------------------------
// Get the authenticated admin user and their role ID
// ---------------------------------------------------------------------------


    $admin = auth()->user();
    $roleId = $admin->roles->first()->id ?? null;

    $menus = DB::table('menus')
    ->join('role_menu', 'menus.id', '=', 'role_menu.menu_id')
    ->where('role_menu.role_id', $roleId)
    ->where('menus.status', 1)
    ->orderBy('menus.sort_order')
    ->select('menus.*')
    ->get();

// ---------------------------------------------------------------------------
//                  PRODUCT STATUS COUNT
//-----------------------------------------------------------------------------
$totalActiveProducts = ProductMaster::where('c_status', 'Y')->count();

$totalInactiveProducts = ProductMaster::where('c_status', 'N')->count();


// -----------------------------------------------------------------------
//                  SALES GROWTH PERCENTAGE
// ------------------------------------------------------------------------
    // Total sales (dashboard)
    $totalSales = DB::table('sales_orders');
   // ->sum('n_sold_price');

   // Today
    $currentSales = DB::table('sales_orders')
    ->whereBetween('created_at', [
        now()->startOfDay(),
        now()->endOfDay()
    ]);
    //->sum('n_sold_price');

    // Yesterday
    $previousSales = DB::table('sales_orders')
    ->whereBetween('created_at', [
        now()->subDay()->startOfDay(),
        now()->subDay()->endOfDay()
    ]);
    //->sum('n_sold_price');




    return view('dashboard', compact(
        'totalSales',
        'currentSales',
        'previousSales',
        'totalActiveProducts',
        'totalInactiveProducts',
        'menus'
));
}

// -----------------------------------------------------------------------
//                View Sales Report with Search and Date Filter
// ------------------------------------------------------------------------
public function viewSalesReport(Request $request)
{
    $search = trim($request->input('search'));
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = EmployeeSalesDraft::with(['employee', 'product', 'store']);

    // check filters
    $hasFilter = $request->filled('search')
              || $request->filled('start_date')
              || $request->filled('end_date');

        if (!$hasFilter) {

         $allSales = new LengthAwarePaginator(
            [],
            0,
            10,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.reports.sales.view-sales-report', compact('allSales'));
    }


    if ($hasFilter) {

        // Search by employee name or code
        if ($request->filled('search')) {
            $query->whereHas('employee', function ($emp) use ($search) {
                $emp->where('c_employee_name', 'like', "%{$search}%")
                    ->orWhere('c_employee_code', 'like', "%{$search}%");
            });
        }

        // Date Filters
        if ($startDate && $endDate) {
            $query->whereBetween('d_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('d_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('d_date', '<=', $endDate);
        }

        // Excel Export
        if ($request->export === 'excel') {
            return Excel::download(
                new SalesViewReportExport($search, $startDate, $endDate),
                'sales-report.xlsx'
            );
        }

        // EXECUTE QUERY ONLY WHEN FILTER EXISTS
        $allSales = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());
    }

    return view('admin.reports.sales.view-sales-report', compact('allSales'));
}
// -----------------------------------------------------------------------
//                View Store Report with Search and Date Filter
// ------------------------------------------------------------------------
public function viewStoreReport(Request $request)
{
    $search = $request->search;
   // Default to yesterday
    $startDate = $request->start_date ?: Carbon::yesterday()->toDateString();
    $endDate = $request->end_date ?: Carbon::yesterday()->toDateString();
    $storeType = $request->store_type;

    $stores = DailyStoreSale::join(
            'store_masters as sm',
            'daily_store_sales.n_store_id',
            '=',
            'sm.n_store_id'
        )
        ->select(
            'sm.c_store_code',
            'sm.c_store_name',
            'daily_store_sales.d_date',
            'daily_store_sales.n_sold_price',
            'daily_store_sales.n_quantity',
            'daily_store_sales.n_buying_rate',
            DB::raw('(daily_store_sales.n_sold_price * daily_store_sales.n_quantity) as sale_amount'),
            DB::raw('(daily_store_sales.n_buying_rate * daily_store_sales.n_quantity) as purchase_amount'),
            DB::raw('((daily_store_sales.n_sold_price - daily_store_sales.n_buying_rate) * daily_store_sales.n_quantity) as profit_amount')
        );

    // SEARCH (store name or code)
    if ($search) {
        $stores->where(function ($q) use ($search) {
            $q->where('sm.c_store_name', 'like', "%{$search}%")
              ->orWhere('sm.c_store_code', 'like', "%{$search}%");
        });
    }

    // STORE TYPE FILTER (Central / Vanitham)
if ($storeType == 'centreal') {
    $stores->where('sm.c_store_email', 'like', '%@centreal%');
}

if ($storeType == 'vanitham') {
    $stores->where('sm.c_store_email', 'like', '%@vanitham%');
}

    // DATE FILTER
if ($startDate && $endDate) {
    $stores->whereBetween(
        DB::raw('DATE(daily_store_sales.d_date)'),
        [$startDate, $endDate]
    );
} elseif ($startDate) {
    $stores->whereRaw('DATE(daily_store_sales.d_date) >= ?', [$startDate]);
} elseif ($endDate) {
    $stores->whereRaw('DATE(daily_store_sales.d_date) <= ?', [$endDate]);
}

    // EXCEL EXPORT
    if ($request->export === 'excel') {
        return Excel::download(
            new StoreSalesReportExport(
                $search,
                $storeType,
                $startDate,
                $endDate
            ),
            'store-sales-report.xlsx'
        );
    }

    // IMPORTANT: NO GROUP BY (each sale row)
    $stores = $stores
        ->orderBy('daily_store_sales.d_date', 'desc')
        ->paginate(10)
        ->appends($request->query());

    return view('admin.reports.store.view-store-report', compact('stores'));
}

}
