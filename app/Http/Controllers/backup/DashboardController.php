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


use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $topPerformerService;

    public function __construct(TopPerformerService $topPerformerService)
    {
        $this->topPerformerService = $topPerformerService;
    }
    
   public function index()
{

// ---------------------------------------------------------------------------
//                  PRODUCT STATUS COUNT
//-----------------------------------------------------------------------------
$totalActiveProducts = ProductMaster::where('c_status', 'Y')->count();

$totalInactiveProducts = ProductMaster::where('c_status', 'N')->count();


// -----------------------------------------------------------------------
//                  SALES GROWTH PERCENTAGE
// ------------------------------------------------------------------------
    // Total sales (dashboard)
    $totalSales = DB::table('daily_store_sales')
    ->sum('n_sold_price');

   // Today
    $currentSales = DB::table('daily_store_sales')
    ->whereBetween('created_at', [
        now()->startOfDay(),
        now()->endOfDay()
    ])
    ->sum('n_sold_price');

    // Yesterday
    $previousSales = DB::table('daily_store_sales')
    ->whereBetween('created_at', [
        now()->subDay()->startOfDay(),
        now()->subDay()->endOfDay()
    ])
    ->sum('n_sold_price');

    // Calculate Growth percentage

            if ($previousSales > 0) {
                $growthPercentageSales = (($currentSales - $previousSales) / $previousSales) * 100;
            } 
            else {
                $growthPercentageSales = 0; 
            }
// -----------------------------------------------------------------------
//                  EMPLOYEES GROWTH PERCENTAGE
// ------------------------------------------------------------------------  
    // Total Active Employee count
    $totalEmployees = EmployeeMaster::where('c_status', 'Y')->count();   
    // Current Month Active Employees count
    $currentEmployees = EmployeeMaster::where('c_status', 'Y')
    ->whereBetween('created_at', [
        now()->startOfMonth(),
        now()->endOfMonth()
    ])
    ->count();
    
    // Previous Month Active Employees count
    $previousEmployees = EmployeeMaster::where('c_status', 'Y')
    ->whereBetween('created_at', [
        now()->subMonth()->startOfMonth(),
        now()->subMonth()->endOfMonth()
    ])
    ->count();

    // Growth Calculation
        if ($previousEmployees > 0) {
            $growthPercentageEmp = (($currentEmployees - $previousEmployees) / $previousEmployees) * 100;
        } else {
            $growthPercentageEmp = 0;
        }
// -----------------------------------------------------------------------
//                  STORE GROWTH PERCENTAGE
// ------------------------------------------------------------------------
    //Total Active Stores
    $totalActiveStores = StoreMaster::where('c_store_status', 'Y')->count();

    // Current Month Active Stores
    $currentActiveStores = StoreMaster::where('c_store_status', 'Y')
    ->whereBetween('created_at', [
        now()->startOfMonth(),
        now()->endOfMonth()
    ])
    ->count();
    // Previous Month Active Stores
    $previousActiveStores = StoreMaster::where('c_store_status', 'Y')
    ->whereBetween('created_at', [
        now()->subMonth()->startOfMonth(),
        now()->subMonth()->endOfMonth()
    ])
    ->count();
        // Growth Calculation
        if ($previousActiveStores > 0) {
            $growthPercentageStores = (($currentActiveStores - $previousActiveStores) / $previousActiveStores) * 100;
        } else {
            $growthPercentageStores = 0;
        }
       
// -----------------------------------------------------------------------
//                  INCENTIVES GROWTH PERCENTAGE
// ------------------------------------------------------------------------
    // Total Incentives till today
    $totalIncentives = DB::table('employee_incentives')
    ->sum('n_incentive_amount');
    
    // Today incentives
    $currentIncentives = DB::table('employee_incentives')
    ->whereBetween('created_at', [
        now()->startOfDay(),
        now()->endOfDay()
    ])
    ->sum('n_incentive_amount');
    
    // Yesterday incentives
    $previousIncentives = DB::table('employee_incentives')
    ->whereBetween('created_at', [
        now()->subDay()->startOfDay(),
        now()->subDay()->endOfDay()
    ])
    ->sum('n_incentive_amount');
   
    // Growth Calculation
        if ($previousIncentives > 0) {
            $growthPercentageIncentives = 
                (($currentIncentives - $previousIncentives) / $previousIncentives) * 100;
        } else {
            $growthPercentageIncentives = 0;
        }

// -----------------------------------------------------------------------
//              VANITHAM OPERATIONS
// ------------------------------------------------------------------------
    
$vanithamStoreIds = StoreMaster::where('c_store_email', 'like', '%@vanitham%')
    ->pluck('n_store_id');
// $date = Carbon::createFromFormat('d/m/Y', '22/06/2026')->format('Y-m-d');
$currentDayVanithamSales = DailyStoreSale::whereIn('n_store_id', $vanithamStoreIds)
    ->whereDate('d_date', Carbon::yesterday())
    ->selectRaw('SUM(n_sold_price * n_quantity) as total_sales')
    ->value('total_sales') ?? 0;

$previousDayVanithamSales = DailyStoreSale::whereIn('n_store_id', $vanithamStoreIds)
    ->whereDate('d_date',  Carbon::yesterday()->subDay())
    ->selectRaw('SUM(n_sold_price * n_quantity) as total_sales')
    ->value('total_sales') ?? 0;

// Growth Calculation
$growthPercentageVanitham = $previousDayVanithamSales > 0
    ? (($currentDayVanithamSales - $previousDayVanithamSales) / $previousDayVanithamSales) * 100
    : ($currentDayVanithamSales > 0 ? 100 : 0);

// -----------------------------------------------------------------------
//            CENTREAL OPERATIONS
// ------------------------------------------------------------------------

$centrealStoreIds = StoreMaster::where('c_store_email', 'like', '%@centreal%')
    ->pluck('n_store_id');
// $date = Carbon::createFromFormat('d/m/Y', '22/06/2026')->format('Y-m-d');
$currentDayCentrealSales = DailyStoreSale::whereIn('n_store_id', $centrealStoreIds)
    ->whereDate('d_date', Carbon::yesterday())
    ->selectRaw('SUM(n_sold_price * n_quantity) as total_sales')
    ->value('total_sales') ?? 0;
$previousDayCentrealSales = DailyStoreSale::whereIn('n_store_id', $centrealStoreIds)
    ->whereDate('d_date', Carbon::yesterday()->subDay())
    ->selectRaw('SUM(n_sold_price * n_quantity) as total_sales')
    ->value('total_sales') ?? 0;
$growthPercentageCentreal = $previousDayCentrealSales > 0
    ? (($currentDayCentrealSales - $previousDayCentrealSales) / $previousDayCentrealSales) * 100
    : ($currentDayCentrealSales > 0 ? 100 : 0);


// --------------------------------------------------------------------------
// CENTREAL STORE INCENTIVES (Cluster, CSA, CA, SM only)
// --------------------------------------------------------------------------


// Get designation IDs
$designationIds = DesignationMaster::whereIn('c_designation', [
    'Cluster',
    'CSA',
    'CA',
    'SM'
])->pluck('n_designation_id');

// Get employees from central stores having these designations
$employeeIds = EmployeeMaster::whereIn('n_store_id', $centrealStoreIds)
    ->whereIn('n_designation_id', $designationIds)
    ->pluck('n_employee_id');

// Current day incentives
$currentDayCentrealIncentives = EmployeeIncentive::whereIn(
        'n_employee_id',
        $employeeIds
    )
    ->whereDate('d_date', Carbon::yesterday())
    ->sum('n_incentive_amount');

// Previous day incentives
$previousDayCentrealIncentives = EmployeeIncentive::whereIn(
        'n_employee_id',
        $employeeIds
    )
    ->whereDate('d_date', Carbon::yesterday()->copy()->subDay())
    ->sum('n_incentive_amount');

// Incentive growth percentage
$growthPercentageCentrealIncentives =
    $previousDayCentrealIncentives > 0
        ? (($currentDayCentrealIncentives - $previousDayCentrealIncentives)
            / $previousDayCentrealIncentives) * 100
        : ($currentDayCentrealIncentives > 0 ? 100 : 0);


// --------------------------------------------------------------------------
// VANITHAM STORE INCENTIVES (Cluster, CSA, CA, SM only)
// --------------------------------------------------------------------------


// Get designation IDs
$designationIds = DesignationMaster::whereIn('c_designation', [
    'Cluster',
    'CSA',
    'CA',
    'SM'
])->pluck('n_designation_id');

// Get employees from central stores having these designations
$employeeIds = EmployeeMaster::whereIn('n_store_id', $vanithamStoreIds)
    ->whereIn('n_designation_id', $designationIds)
    ->pluck('n_employee_id');

// Current day incentives
$currentDayVanithamIncentives = EmployeeIncentive::whereIn(
        'n_employee_id',
        $employeeIds
    )
    ->whereDate('d_date', Carbon::yesterday())
    ->sum('n_incentive_amount');

// Previous day incentives
$previousDayVanithamIncentives = EmployeeIncentive::whereIn(
        'n_employee_id',
        $employeeIds
    )
    ->whereDate('d_date', Carbon::yesterday()->copy()->subDay())
    ->sum('n_incentive_amount');

// Incentive growth percentage
$growthPercentageVanithamIncentives =
    $previousDayVanithamIncentives > 0
        ? (($currentDayVanithamIncentives - $previousDayVanithamIncentives)
            / $previousDayVanithamIncentives) * 100
        : ($currentDayVanithamIncentives > 0 ? 100 : 0);




// -----------------------------------------------------------------------
//              RECENTLY ADDED SALES
// ------------------------------------------------------------------------
   $recentSales = EmployeeSalesDraft::with(['employee', 'product', 'store'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

   
// -----------------------------------------------------------------------
//                 TOP PERFORMERS VANITHAM(CA,CSA,SM,CLUSTER)
// ------------------------------------------------------------------------

    $topPerformers = $this->topPerformerService->getAllTopPerformers('@vanitham');

    $topVanithamCA = $topPerformers['topCA'];
    $topVanithamCSA = $topPerformers['topCSA'];
    $topVanithamSM = $topPerformers['topSM'];
    $topVanithamCluster = $topPerformers['topCluster'];

// -----------------------------------------------------------------------
//                 TOP PERFORMERS CENTREAL(CA,CSA,SM,CLUSTER)
// ------------------------------------------------------------------------
    $topPerformers = $this->topPerformerService->getAllTopPerformers('@centreal');

    $topCentrealCA = $topPerformers['topCA'];
    $topCentrealCSA = $topPerformers['topCSA'];
    $topCentrealSM = $topPerformers['topSM'];
    $topCentrealCluster = $topPerformers['topCluster'];

// -----------------------------------------------------------------------
//                 TOP STORES ON SALES
// ------------------------------------------------------------------------

   $topStores = DB::table('daily_store_sales as dss')
    ->join('store_masters as sm', 'dss.n_store_id', '=', 'sm.n_store_id')
    ->whereDate('dss.d_date', Carbon::yesterday())
    ->select(
        'sm.n_store_id',
        'sm.c_store_name',
        'sm.c_store_code',
        DB::raw('SUM(dss.n_sold_price * dss.n_quantity) as total_sales')
    )
    ->groupBy(
        'sm.n_store_id',
        'sm.c_store_name',
        'sm.c_store_code'
    )
    ->orderByDesc('total_sales')
    ->limit(5)
    ->get();
// -----------------------------------------------------------------------
//                PENDING STORES
// ------------------------------------------------------------------------
  
   $pendingSalesByStore = DailyStoreSale::with('store')
    ->select('n_store_id', DB::raw('COUNT(*) as total_sales_pending'))
    ->where('is_incentive_calculated', 0)
    ->whereDate('d_date', Carbon::yesterday())
    ->groupBy('n_store_id')
    ->orderByDesc('total_sales_pending')
    ->limit(5)
    ->get();

    return view('dashboard', compact(
        'totalSales',
        'totalEmployees',
        'totalActiveStores',
        'totalIncentives',
        'recentSales',
        'growthPercentageSales',
        'growthPercentageEmp',
        'growthPercentageStores',
        'growthPercentageIncentives',
        'topVanithamCA',
        'topVanithamCSA',
        'topVanithamSM',
        'topVanithamCluster',
        'topCentrealCA',
        'topCentrealCSA',
        'topCentrealSM',
        'topCentrealCluster',
        'topStores',
        'pendingSalesByStore',
        'totalActiveProducts',
        'totalInactiveProducts', 
        'currentDayVanithamSales',
        'currentDayCentrealSales',
        'growthPercentageVanitham',
        'growthPercentageCentreal',
        'currentDayCentrealIncentives',
        'growthPercentageCentrealIncentives',
        'currentDayVanithamIncentives',
        'growthPercentageVanithamIncentives'

        
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