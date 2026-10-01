<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreMaster;
use App\Services\HierarchyScope;
use App\Support\Geo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Orders vs franchises on a map, to spot under-served areas
 * (orders further than config('spc.underserved_km') from every active franchise).
 */
class CoverageMapController extends Controller
{
    private const MAX_ORDERS = 3000;

    public function index(Request $request)
    {
        $admin = auth('web')->user();
        $scope = HierarchyScope::employeeIds($admin);
        $days = min(365, max(7, (int) $request->query('days', 90)));
        $threshold = (float) config('spc.underserved_km', 25);

        $franchises = StoreMaster::where('c_store_status', 'Y')
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->get(['n_store_id', 'c_store_name', 'c_store_code', 'latitude', 'longitude'])
            ->filter(fn ($s) => Geo::valid($s->latitude, $s->longitude))->values();

        $base = fn () => DB::table('sales_orders as so')
            ->whereNull('so.deleted_at')
            ->whereNotIn('so.c_order_status', ['Rejected', 'Cancelled'])
            ->where('so.d_date', '>=', now()->subDays($days - 1)->toDateString())
            ->when($scope !== null, fn ($q) => $q->whereIn('so.farm_care_advisor_id', $scope ?: [0]));

        $totalOrders = (int) $base()->count();

        $orders = $base()
            ->whereNotNull('so.latitude')->whereNotNull('so.longitude')
            ->leftJoin('districts as d', 'd.id', '=', 'so.n_district_id')
            ->orderByDesc('so.d_date')->limit(self::MAX_ORDERS)
            ->get(['so.n_sl_no as id', 'so.c_order_no as no', 'so.d_date as date', 'so.latitude', 'so.longitude',
                'so.n_net_sales_amount as amount', 'so.nearest_franchise_id as fid', 'so.franchise_distance_km as assigned_km',
                'd.district_name as district'])
            ->filter(fn ($o) => Geo::valid($o->latitude, $o->longitude))->values();

        $points = [];
        $underserved = 0;
        $byDistrict = [];

        foreach ($orders as $o) {
            $nearest = $franchises->isEmpty() ? [] : Geo::rank((float) $o->latitude, (float) $o->longitude, $franchises, null, 1);
            $km = $nearest[0]['km'] ?? null;
            $far = $km === null || $km > $threshold;

            if ($far) {
                $underserved++;
                $key = $o->district ?: 'Unknown';
                $byDistrict[$key] = ($byDistrict[$key] ?? 0) + 1;
            }

            $points[] = [
                'lat' => round((float) $o->latitude, 6), 'lng' => round((float) $o->longitude, 6),
                'no' => $o->no, 'date' => $o->date, 'amount' => (float) $o->amount,
                'km' => $km, 'far' => $far, 'district' => $o->district,
            ];
        }

        arsort($byDistrict);

        // Orders per franchise (as assigned on the order) with average distance.
        $perFranchise = DB::table('sales_orders as so')
            ->join('store_masters as st', 'st.n_store_id', '=', 'so.nearest_franchise_id')
            ->whereNull('so.deleted_at')
            ->whereNotIn('so.c_order_status', ['Rejected', 'Cancelled'])
            ->where('so.d_date', '>=', now()->subDays($days - 1)->toDateString())
            ->when($scope !== null, fn ($q) => $q->whereIn('so.farm_care_advisor_id', $scope ?: [0]))
            ->groupBy('st.n_store_id', 'st.c_store_name')
            ->selectRaw('st.c_store_name as name, COUNT(*) as orders, ROUND(AVG(so.franchise_distance_km),1) as avg_km, COALESCE(SUM(so.n_net_sales_amount),0) as sales')
            ->orderByDesc('orders')->limit(10)->get();

        return view('admin.coverage-map.index', [
            'days' => $days,
            'threshold' => $threshold,
            'franchises' => $franchises->map(fn ($s) => [
                'name' => $s->c_store_name, 'code' => $s->c_store_code,
                'lat' => (float) $s->latitude, 'lng' => (float) $s->longitude,
            ])->all(),
            'points' => $points,
            'stats' => [
                'total' => $totalOrders,
                'mapped' => count($points),
                'unmapped' => max(0, $totalOrders - count($points)),
                'underserved' => $underserved,
                'capped' => $totalOrders > self::MAX_ORDERS,
                'franchises' => $franchises->count(),
            ],
            'byDistrict' => array_slice($byDistrict, 0, 6, true),
            'perFranchise' => $perFranchise,
        ]);
    }
}
