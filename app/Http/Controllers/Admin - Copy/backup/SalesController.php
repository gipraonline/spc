<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSalesUpload;
use App\Jobs\ProcessReturnsUpload;
use App\Models\DailyStoreSale;
use App\Models\DesignationMaster;
use App\Models\EmployeeIncentive;
use App\Models\EmployeeMaster;
use App\Models\ProductMaster;
use App\Models\AdminSaleDraft;
use App\Models\AdminSaleUpload;
use App\Models\ReturnSaleDraft;
use App\Models\ReturnDraftUpload;
use App\Models\StoreMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Exports\SaleReturnsReportExport;

class SalesController extends Controller
{
    public function index()
    {
        $sales = DailyStoreSale::with('employee', 'product')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $employees = EmployeeMaster::where('c_status', 'Y')->get();
        $products = ProductMaster::where('c_status', 'Y')->get();

        return view('admin.sales.create', compact('employees', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'n_employee_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('employee_masters', 'n_employee_id'),
            ],
            'n_product_id' => 'required|exists:product_masters,n_product_id',
            'n_quantity' => 'required|integer|min:1',
            'd_date' => 'required|date',
        ]);

        $employee = EmployeeMaster::find($validated['n_employee_id']);
        $product = ProductMaster::find($validated['n_product_id']);

        $buyingRate = (float) ($product->n_purchase_price ?? 0);
        $sellingPrice = (float) ($product->n_selling_price ?? 0);
        $qty = (int) ($validated['n_quantity'] ?? 1);
        $buyingRate = (float) ($product->n_purchase_price ?? 0);
        $sellingPrice = (float) ($product->n_selling_price ?? 0);
        $qty = (int) ($validated['n_quantity'] ?? 1);

        $sale = DailyStoreSale::create([
            'd_date' => $validated['d_date'],
            'n_store_id' => $employee->n_store_id ?? null,
            'n_employee_id' => $validated['n_employee_id'],
            'n_product_id' => $validated['n_product_id'],
            'n_sold_price' => $sellingPrice,
            'n_buying_rate' => $buyingRate,
            'n_quantity' => $qty,
            'c_bill_no' => 'BILL-' . time(),
            'd_bill_date' => $validated['d_date'],
            'c_approve' => 'N',
            'c_status' => 'Y',
        ]);

        $incentiveService = new \App\Services\IncentiveCalculationService();
        $incentiveService->calculateSaleIncentives($sale->n_slno);

        return redirect()->route('admin.sales.index')->with('success', 'Sales entry created successfully.');
    }

    public function show(DailyStoreSale $sale)
    {
        $sale->load('store');
        $date = EmployeeIncentive::where('n_slno', $sale->n_slno)->first();
        //dd($date->toSql(), $date->getBindings());
        $employeeIncentiveDate = isset($date->created_at) ? date('d-m-Y', strtotime($date->created_at)) : '';
        return view('admin.sales.show', compact('sale', 'employeeIncentiveDate'));
    }

    public function edit(DailyStoreSale $sale)
    {
        $employees = EmployeeMaster::where('c_status', 'Y')->where('n_pool_id', 0)->get();
        $products = ProductMaster::where('c_status', 'Y')->get();

        return view('admin.sales.edit', compact('sale', 'employees', 'products'));
    }

    public function update(Request $request, DailyStoreSale $sale)
    {
        $validated = $request->validate([
            'n_employee_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('employee_masters', 'n_employee_id')->where('n_pool_id', 0),
            ],
            'n_product_id' => 'required|exists:product_masters,n_product_id',
            'n_quantity' => 'required|integer|min:1',
            'd_date' => 'required|date',
        ]);

        $employee = EmployeeMaster::find($validated['n_employee_id']);
        $product = ProductMaster::find($validated['n_product_id']);

        $validated['n_store_id'] = $employee->n_store_id ?? null;
        $validated['n_sold_price'] = (float) ($product->n_selling_price ?? 0);
        $validated['n_buying_rate'] = (float) ($product->n_purchase_price ?? 0);
        $validated['d_bill_date'] = $validated['d_date'];

        $sale->update($validated);

        EmployeeIncentive::where('n_slno', $sale->n_slno)->delete();

        $incentiveService = new \App\Services\IncentiveCalculationService();
        $incentiveService->calculateSaleIncentives($sale->n_slno);

        return redirect()->route('admin.sales.index')->with('success', 'Sales entry updated successfully.');
    }

    public function destroy(DailyStoreSale $sale)
    {
        $sale->delete();
        return redirect()->route('admin.sales.index')->with('success', 'Sales entry deleted successfully.');
    }

    // ─── Bulk Upload ───────────────────────────────────────────────────────────

    public function bulkUpload()
    {
        $hasDrafts = AdminSaleDraft::exists();
        $pendingCount = AdminSaleDraft::where('c_status', 'pending')->count();
        return view('admin.sales.bulk-upload', compact('hasDrafts', 'pendingCount'));
    }

    public function processBulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        AdminSaleDraft::truncate();

        $batchId = (string) Str::uuid();
        $path = $request->file('file')->store('uploads/sales');

        ProcessSalesUpload::dispatch($path, $batchId);

        return redirect()->route('admin.sales.drafts')
            ->with('info', 'File uploaded. Processing in background — refresh to see results.');
    }

    public function drafts(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $drafts = AdminSaleDraft::orderBy('id')->paginate($perPage)->withQueryString();

        $counts = [
            'total' => AdminSaleDraft::count(),
            'valid' => AdminSaleDraft::where('c_status', 'valid')->count(),
            'error' => AdminSaleDraft::where('c_status', 'error')->count(),
            'pending' => AdminSaleDraft::where('c_status', 'pending')->count(),
        ];
        return view('admin.sales.drafts', compact('drafts', 'counts', 'perPage'));
    }

    public function showDraftBill($bill_no)
    {
        $drafts = AdminSaleDraft::where('c_billno', $bill_no)->get();
        if ($drafts->isEmpty()) {
            return redirect()->route('admin.sales.drafts')->with('error', 'Bill not found.');
        }

        $totalAmount = $drafts->sum(function ($d) {
            return $d->n_selling_price * $d->n_quantity;
        });

        return view('admin.sales.draft-bill', compact('drafts', 'bill_no', 'totalAmount'));
    }

    public function confirmDrafts()
    {

        $validDrafts = AdminSaleDraft::where('c_status', 'valid')->get();
        $imported = 0;

        foreach ($validDrafts as $draft) {
            // 1. Log unnormalized data for tracing
            \App\Models\AdminSaleUnnormalizedLog::create([
                'admin_sale_draft_id' => $draft->id,
                'n_batch_id' => $draft->n_batch_id,
                'd_date' => $draft->d_date,
                'c_store_code' => $draft->c_store_code,
                'c_billno' => $draft->c_billno,
                'c_item_code' => $draft->c_item_code,
                'n_quantity' => $draft->n_quantity,
                'c_status' => $draft->c_status,
                'c_validation_message' => $draft->c_validation_message,
            ]);

            // 2. Normalize data for lookup/storage (Uppercase, no non-alphanumeric/whitespace)
            $normalizedStoreCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $draft->c_store_code));
            $normalizedItemCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $draft->c_item_code));
            $normalizedBillNo = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $draft->c_billno));

            $store = StoreMaster::where('c_store_code', $normalizedStoreCode)->first();
            $product = ProductMaster::where('c_product_code', $normalizedItemCode)->first();

            if (!$store || !$product) {
                continue;
            }

            $billNoToUse = $normalizedBillNo ?: 'BULK-' . $draft->id;

            // Check duplicate
            $exists = AdminSaleUpload::where('c_bill_no', $billNoToUse)
                ->where('n_store_id', $store->n_store_id)
                ->exists();

            if ($exists) {
                continue; // Skip duplicate record
            }

            AdminSaleUpload::create([
                'd_date' => $draft->d_date,
                'n_store_id' => $store->n_store_id,
                'n_product_id' => $product->n_product_id,
                'n_sold_price' => $product->n_selling_price,
                'n_buying_rate' => $product->n_purchase_price,
                'n_quantity' => $draft->n_quantity,
                'c_bill_no' => $normalizedBillNo ?: 'BULK-' . $draft->id,
                'd_bill_date' => $draft->d_date,
                'c_approve' => 'N',
                'c_status' => 'Y',
                'batch_id' => $draft->n_batch_id,
            ]);

            $imported++;
        }

        AdminSaleDraft::truncate();
        return redirect()->route('admin.sales.uploads.report')
             ->with('success', $imported . ' sale(s) imported successfully.');

        // return redirect()->route('admin.sales.index')
        //     ->with('success', $imported . ' sale(s) imported successfully.');
    }

    public function cancelDrafts()
    {
        AdminSaleDraft::truncate();
        return redirect()->route('admin.sales.bulk-upload')
            ->with('success', 'All drafts cancelled.');
    }

    // ──────────────────────────── REPORTS ───────────────────────────────────────────────────────────

    public function salesReport(Request $request)
    {

        // Filters
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $employeeId = $request->employee_id;
        $storeId = $request->store_id;
        $incentiveStatus = $request->incentive_status;
        $storeCode = $request->store_code;
        $productName = $request->product_name;
        $billNo = $request->bill_no;



        // Dropdown data
        $employees = DB::table('employee_masters as e')
            ->join('daily_store_sales as dss', 'e.n_employee_id', '=', 'dss.n_employee_id')
            ->select('e.n_employee_id', 'e.c_employee_name')
            ->distinct()
            ->orderBy('e.c_employee_name')
            ->get();

        $stores = DB::table('store_masters as s')
            ->join('daily_store_sales as dss', 's.n_store_id', '=', 'dss.n_store_id')
            ->select('s.n_store_id', 's.c_store_name')
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('dss.d_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('dss.d_date', '<=', $endDate);
            })
            ->distinct()
            ->orderBy('s.c_store_name')
            ->get();

        $storeDropdown = DB::table('store_masters as s')
            ->select('s.n_store_id', 's.c_store_name')
            ->orderBy('s.c_store_name')
            ->get();

        // Main Query
        $query = DB::table('daily_store_sales as dss')
            ->join('employee_masters as e', 'dss.n_employee_id', '=', 'e.n_employee_id')
            ->join('store_masters as s', 'dss.n_store_id', '=', 's.n_store_id')
            ->join('product_masters as p', 'dss.n_product_id', '=', 'p.n_product_id')
            ->select(
                'dss.d_date',
                'e.c_employee_name as c_employee_name',
                's.c_store_name as c_store_name',
                's.c_store_code as c_store_code',
                'p.c_product_name as c_product_name',
                'dss.c_bill_no',
                'dss.n_sold_price',
                'dss.n_buying_rate',
                'dss.n_quantity',
                'dss.is_incentive_calculated',
                DB::raw('(dss.n_sold_price * dss.n_quantity) as total_price'),
                DB::raw('(0.20 * (dss.n_sold_price - dss.n_buying_rate) * dss.n_quantity) as n_total_margin_amount')
            );

        // Date filters
        if (!empty($startDate)) {
            $query->whereDate('dss.d_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('dss.d_date', '<=', $endDate);
        }

        // Employee filter
        if (!empty($employeeId)) {
            $query->where('dss.n_employee_id', $employeeId);
        }

        // Store filter  FIXED
        if (!empty($storeId)) {
            $query->where('dss.n_store_id', $storeId);
        }

        // Incentive Status filter
        if ($incentiveStatus !== null && $incentiveStatus !== "") {
            $query->where('dss.is_incentive_calculated', $incentiveStatus);
        }

        // Store Code filter
        if (!empty($storeCode)) {

            $query->where('s.c_store_code', 'LIKE', '%' . $storeCode . '%');

        }

        // Product Name filter
        if (!empty($productName)) {

            $query->where('p.c_product_name', 'LIKE', '%' . $productName . '%');

        }

        // Bill No filter
        if (!empty($billNo)) {

            $query->where('dss.c_bill_no', 'LIKE', '%' . $billNo . '%');

        }



        // Final Result
        $report = $query
            ->orderBy('dss.d_date', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('admin.reports.sales.verified-sales-report', compact('report', 'employees', 'stores','storeDropdown'));
    }
    public function exportSalesReport(Request $request)
    {
        return Excel::download(
            new SalesReportExport($request->all()),
            'sales_report.xlsx'
        );
    }


    public function salesUploadsReport(Request $request)
    {
        $query = AdminSaleUpload::query()
            ->join('product_masters as p', 'admin_sale_uploads.n_product_id', '=', 'p.n_product_id')
            ->join('store_masters as s', 'admin_sale_uploads.n_store_id', '=', 's.n_store_id')
            ->select(
                'admin_sale_uploads.*',
                'p.c_product_code',
                'p.c_product_name',
                's.c_store_code',
                's.c_store_name'
            );


        //Search (Bill No + Product Code)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('admin_sale_uploads.c_bill_no', 'like', "%$search%")
                    ->orWhere('p.c_product_code', 'like', "%$search%");
            });
        }

        //Filter by Bill No
        if ($request->filled('bill_no')) {
            $query->where('admin_sale_uploads.c_bill_no', $request->bill_no);
        }

        // Filter by Product
        if ($request->filled('product_id')) {
            $query->where('admin_sale_uploads.n_product_id', $request->product_id);
        }

        // Filter From Date
        if ($request->filled('start_date')) {
            $query->whereDate('admin_sale_uploads.d_date', '>=', $request->start_date);
        }

        // Filter To Date
        if ($request->filled('end_date')) {
            $query->whereDate('admin_sale_uploads.d_date', '<=', $request->end_date);
        }

        $sales = $query->latest()->paginate(15);

        return view('admin.reports.sales.sales-report', compact('sales'));
    }

    public function saleReturnsReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $employeeId = $request->employee_id;
        $storeId = $request->store_id;
        $returnStatus = $request->return_status;

        // Dropdown data
        $employees = DB::table('employee_masters as e')
            ->join('employee_sales_drafts as esd', 'e.n_employee_id', '=', 'esd.n_employee_id')
            ->select('e.n_employee_id', 'e.c_employee_name')
            ->whereNotNull('esd.return_status')
            ->distinct()
            ->orderBy('e.c_employee_name')
            ->get();

        $stores = DB::table('store_masters as s')
            ->join('employee_sales_drafts as esd', 's.n_store_id', '=', 'esd.n_store_id')
            ->select('s.n_store_id', 's.c_store_name')
            ->whereNotNull('esd.return_status')
            ->distinct()
            ->orderBy('s.c_store_name')
            ->get();

        // Main Query
        $query = DB::table('employee_sales_drafts as esd')
            ->join('employee_masters as e', 'esd.n_employee_id', '=', 'e.n_employee_id')
            ->join('store_masters as s', 'esd.n_store_id', '=', 's.n_store_id')
            ->join('product_masters as p', 'esd.n_product_id', '=', 'p.n_product_id')
            // Join with daily_store_sales to check if incentive was calculated
            ->leftJoin('daily_store_sales as dss', function($join) {
                $join->on('esd.c_bill_no', '=', 'dss.c_bill_no')
                     ->on('esd.n_product_id', '=', 'dss.n_product_id')
                     ->on('esd.n_employee_id', '=', 'dss.n_employee_id');
            })
            ->select(
                'esd.id',
                'esd.d_date',
                'e.c_employee_name',
                's.c_store_name',
                's.c_store_code',
                'p.c_product_name',
                'esd.c_bill_no',
                'esd.n_sold_price',
                'esd.n_quantity',
                'esd.return_status',
                DB::raw('COALESCE(dss.is_incentive_calculated, 0) as is_incentive_calculated'),
                DB::raw('(esd.n_sold_price * esd.n_quantity) as total_price')
            )
            ->whereNotNull('esd.return_status');

        // Apply filters
        if (!empty($startDate)) {
            $query->whereDate('esd.d_date', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('esd.d_date', '<=', $endDate);
        }
        if (!empty($employeeId)) {
            $query->where('esd.n_employee_id', $employeeId);
        }
        if (!empty($storeId)) {
            $query->where('esd.n_store_id', $storeId);
        }
        if (!empty($returnStatus)) {
            $query->where('esd.return_status', $returnStatus);
        }

        $report = $query->orderBy('esd.d_date', 'desc')
            ->paginate(15)
            ->appends($request->all());

        return view('admin.reports.sales.returns-report', compact('report', 'employees', 'stores'));
    }

    public function exportSaleReturnsReport(Request $request)
    {
        return Excel::download(
            new SaleReturnsReportExport($request->all()),
            'sale_returns_report.xlsx'
        );
    }

    // ─── Return Bulk Upload ─────────────────────────────────────────────────────

    public function returnBulkUpload()
    {
        $hasDrafts = ReturnSaleDraft::exists();
        $pendingCount = ReturnSaleDraft::where('c_status', 'pending')->count();
        return view('admin.sales.return-bulk-upload', compact('hasDrafts', 'pendingCount'));
    }

    public function processReturnBulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        ReturnSaleDraft::truncate();

        $batchId = (string) Str::uuid();
        $path = $request->file('file')->store('uploads/returns');

        ProcessReturnsUpload::dispatch($path, $batchId);

        return redirect()->route('admin.returns.drafts')
            ->with('info', 'File uploaded. Processing in background — refresh to see results.');
    }

    public function returnDrafts(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $drafts = ReturnSaleDraft::orderBy('id')->paginate($perPage)->withQueryString();

        $counts = [
            'total' => ReturnSaleDraft::count(),
            'valid' => ReturnSaleDraft::where('c_status', 'valid')->count(),
            'error' => ReturnSaleDraft::where('c_status', 'error')->count(),
            'pending' => ReturnSaleDraft::where('c_status', 'pending')->count(),
        ];
        return view('admin.sales.return-drafts', compact('drafts', 'counts', 'perPage'));
    }

    public function showReturnDraftBill($bill_no)
    {
        $drafts = ReturnSaleDraft::where('c_billno', $bill_no)->get();
        if ($drafts->isEmpty()) {
            return redirect()->route('admin.returns.drafts')->with('error', 'Bill not found.');
        }

        $totalAmount = $drafts->sum(function ($d) {
            return $d->n_selling_price * $d->n_quantity;
        });

        return view('admin.sales.return-draft-bill', compact('drafts', 'bill_no', 'totalAmount'));
    }

    public function confirmReturnDrafts()
    {
        $validDrafts = ReturnSaleDraft::where('c_status', 'valid')->get();
        $imported = 0;

        foreach ($validDrafts as $draft) {
            $normalizedStoreCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $draft->c_store_code));
            $normalizedItemCode = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $draft->c_item_code));
            $normalizedBillNo = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $draft->c_billno));

            $store = StoreMaster::where('c_store_code', $normalizedStoreCode)->first();
            $product = ProductMaster::where('c_product_code', $normalizedItemCode)->first();

            if (!$store || !$product) {
                continue;
            }

            $billNoToUse = $normalizedBillNo ?: 'RBULK-' . $draft->id;

            // Check duplicate return
            $exists = ReturnDraftUpload::where('c_bill_no', $billNoToUse)
                ->where('n_store_id', $store->n_store_id)
                ->where('n_product_id', $product->n_product_id)
                ->exists();

            if ($exists) {
                continue;
            }

            ReturnDraftUpload::create([
                'd_date' => $draft->d_date,
                'n_store_id' => $store->n_store_id,
                'n_product_id' => $product->n_product_id,
                'n_sold_price' => $product->n_selling_price,
                'n_buying_rate' => $product->n_purchase_price,
                'n_quantity' => $draft->n_quantity,
                'c_bill_no' => $normalizedBillNo ?: 'RBULK-' . $draft->id,
                'd_bill_date' => $draft->d_date,
                'c_status' => 'Y',
                'batch_id' => $draft->n_batch_id,
            ]);

            $imported++;
        }

        ReturnSaleDraft::truncate();
        return redirect()->route('admin.returns.bulk-upload')
             ->with('success', $imported . ' return(s) imported successfully.');
    }

    public function cancelReturnDrafts()
    {
        ReturnSaleDraft::truncate();
        return redirect()->route('admin.returns.bulk-upload')
            ->with('success', 'All return drafts cancelled.');
    }
}
