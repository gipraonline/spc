<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TableExport;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use App\Models\StoreMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Franchise-wise sales summary: one row per franchise (orders are grouped by
 * sales_orders.nearest_franchise_id) with order count and amounts.
 */
class FranchiseSalesReportController extends Controller
{
    /**
     * Aggregated rows for the current filters.
     */
    private function summary(Request $request)
    {
        $q = DB::table('sales_orders as so')
            ->leftJoin('store_masters as st', 'st.n_store_id', '=', 'so.nearest_franchise_id')
            ->whereNull('so.deleted_at');

        if ($request->filled('from_date')) {
            $q->whereDate('so.d_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $q->whereDate('so.d_date', '<=', $request->to_date);
        }

        if ($request->filled('franchise_id')) {
            $q->where('so.nearest_franchise_id', $request->franchise_id);
        }

        if ($request->filled('state_id')) {
            $q->where('st.n_state_id', $request->state_id);
        }

        if ($request->filled('district_id')) {
            $q->where('st.n_district_id', $request->district_id);
        }

        if ($request->filled('payment_status')) {
            $status = strtolower($request->payment_status);
            $status === 'paid'
                ? $q->whereRaw("LOWER(COALESCE(so.payment_status, '')) = 'paid'")
                : $q->whereRaw("LOWER(COALESCE(so.payment_status, 'pending')) <> 'paid'");
        }

        $rows = $q
            ->groupBy('so.nearest_franchise_id', 'st.c_store_code', 'st.c_store_name', 'st.n_district_id')
            ->selectRaw("
                so.nearest_franchise_id                                         AS franchise_id,
                st.c_store_code                                                 AS store_code,
                st.c_store_name                                                 AS store_name,
                st.n_district_id                                                AS district_id,
                COUNT(*)                                                        AS orders,
                COALESCE(SUM(so.n_total_sales_amount), 0)                       AS gross,
                COALESCE(SUM(so.n_total_discount + so.n_product_discount_total), 0) AS discount,
                COALESCE(SUM(so.n_total_gst), 0)                                AS gst,
                COALESCE(SUM(so.n_net_sales_amount), 0)                         AS net,
                COALESCE(SUM(CASE WHEN LOWER(COALESCE(so.payment_status, '')) = 'paid'
                                  THEN so.n_net_sales_amount ELSE 0 END), 0)    AS paid
            ")
            ->orderByDesc('net')
            ->get();

        $districts = District::pluck('district_name', 'id');

        return $rows->map(function ($r) use ($districts) {
            $r->district = $districts[$r->district_id] ?? null;
            $r->pending = (float) $r->net - (float) $r->paid;
            $r->store_name = $r->franchise_id ? $r->store_name : 'No franchise assigned';

            return $r;
        });
    }

    public function index(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $rows = $this->summary($request);

        $totals = (object) [
            'orders' => $rows->sum('orders'),
            'gross' => $rows->sum('gross'),
            'discount' => $rows->sum('discount'),
            'gst' => $rows->sum('gst'),
            'net' => $rows->sum('net'),
            'paid' => $rows->sum('paid'),
            'pending' => $rows->sum('pending'),
        ];

        $states = State::where('status', 1)->orderBy('name')->get();
        $districts = $request->filled('state_id')
            ? District::where('state_id', $request->state_id)->orderBy('district_name')->get()
            : collect();
        $franchises = StoreMaster::where('c_store_status', 'Y')->orderBy('c_store_name')->get();

        return view('admin.reports.franchise-sales.index', compact('rows', 'totals', 'states', 'districts', 'franchises'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $rows = $this->summary($request);

        $data = [];
        $i = 0;
        foreach ($rows as $r) {
            $data[] = [
                ++$i, $r->store_code, $r->store_name, $r->district,
                (int) $r->orders, (float) $r->gross, (float) $r->discount, (float) $r->gst,
                (float) $r->net, (float) $r->paid, (float) $r->pending,
            ];
        }

        $data[] = [
            '', '', 'TOTAL', '',
            (int) $rows->sum('orders'), (float) $rows->sum('gross'), (float) $rows->sum('discount'),
            (float) $rows->sum('gst'), (float) $rows->sum('net'), (float) $rows->sum('paid'),
            (float) $rows->sum('pending'),
        ];

        return Excel::download(
            new TableExport(
                ['Sl No', 'Franchise Code', 'Franchise', 'District', 'Orders', 'Sales Total', 'Discount', 'GST',
                    'Net Amount', 'Paid', 'Pending'],
                $data,
                ['B'],
                ['F', 'G', 'H', 'I', 'J', 'K']
            ),
            'franchise-sales-summary-'.now()->format('Ymd-His').'.xlsx'
        );
    }
}
