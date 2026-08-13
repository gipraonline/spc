<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IncentiveSalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CustomerMaster;
use App\Models\DesignationMaster;
use App\Models\District;
use App\Models\EmployeeMaster;
use App\Models\OrderProduct;
use App\Models\ProductMaster;
use App\Models\SalesApproval;
use App\Models\SalesOrder;
use App\Models\State;
use App\Models\StoreMaster;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    // public function index(Request $request)
    //     {

    //         $query = SalesOrder::query();
    //         $query=$query->with('employee','franchise')->whereNull('deleted_at');
    //         /*
    //         |--------------------------------------------------------------------------
    //         | Search by Employee Name or Code
    //         |--------------------------------------------------------------------------
    //         */
    //         if ($request->filled('search')) {
    //             $search = $request->search;

    //             $query->whereHas('employee', function ($q) use ($search) {
    //                 $q->where('c_employee_name', 'like', "%{$search}%")
    //                 ->orWhere('c_employee_code', 'like', "%{$search}%");
    //             });
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Date Filters
    //         |--------------------------------------------------------------------------
    //         */

    //         // Date range
    //         if ($request->filled('start_date') && $request->filled('end_date')) {
    //             $query->where('d_date','>=',$request->start_date,)
    //                 ->where('d_date','<=',$request->end_date,);
    //         }

    //         // From date only
    //         elseif ($request->filled('start_date')) {
    //             $query->whereDate('d_date', '>=', $request->start_date);
    //         }

    //         // To date only
    //         elseif ($request->filled('end_date')) {
    //             $query->whereDate('d_date', '<=', $request->end_date);
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Export Excel
    //         |--------------------------------------------------------------------------
    //         */
    //         if ($request->export === 'excel') {

    //             $sales = $query
    //                 ->orderBy('d_date', 'desc')
    //                 ->get();

    //             return Excel::download(
    //                 new IncentiveSalesReportExport($sales),
    //                 'sales-report.xlsx'
    //             );
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Page Display
    //         |--------------------------------------------------------------------------
    //         */
    //         $sales = $query
    //             ->orderBy('d_date', 'desc')
    //             ->paginate(20)
    //             ->withQueryString();
    //     //dd($sales);
    //         return view('admin.sales.index', compact('sales'));
    //

    // FCA can access Sales Orders only for the current business date
    private function isFca(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->where('identifier', 'FCA')
                ->exists();
    }

    private function isFcaToday(SalesOrder $sale): bool
    {
        return ! $this->isFca()
            || Carbon::parse($sale->d_date)->isToday();
    }

    public function index(Request $request)
    {
        $query = SalesOrder::with('employee', 'franchise', 'customer')
            ->whereNull('deleted_at');

        $user = Auth::user();
        // dd($user);

        /*
        |--------------------------------------------------------------------------
        | Farm Care Advisor Access
        |--------------------------------------------------------------------------
        | FCA can see only their own sales for the current day.
        | Other roles can see sales according to their existing permissions.
        |--------------------------------------------------------------------------
        */

        $isFarmCareAdvisor = $this->isFca();

        if ($this->isFca()) {

            // Logged-in FCA's employee ID
            $employeeId = $user->n_employee_id;

            $query->where('farm_care_advisor_id', $employeeId)
                ->whereDate('d_date', today());
        }

        /*
        |--------------------------------------------------------------------------
        | Search by Employee Name or Code
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('employee', function ($q) use ($search) {

                $q->where('c_employee_name', 'like', "%{$search}%")
                    ->orWhere('c_employee_code', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date') && $request->filled('end_date')) {

            $query->whereDate('d_date', '>=', $request->start_date)
                ->whereDate('d_date', '<=', $request->end_date);

        } elseif ($request->filled('start_date')) {

            $query->whereDate('d_date', '>=', $request->start_date);

        } elseif ($request->filled('end_date')) {

            $query->whereDate('d_date', '<=', $request->end_date);
        }

        /*
        |--------------------------------------------------------------------------
        | Export Excel
        |--------------------------------------------------------------------------
        */

        if ($request->export === 'excel') {

            $sales = $query
                ->orderBy('d_date', 'desc')
                ->get();

            return Excel::download(
                new IncentiveSalesReportExport($sales),
                'sales-report.xlsx'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $sales = $query
            ->orderBy('created_at', 'asc')
            ->orderBy('n_sl_no', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.sales.index', compact(
            'sales',
            'isFarmCareAdvisor'
        ));
    }

    public function create()
    {

        $employees = Admin::join('employee_masters as em', 'em.n_employee_id', 'admins.n_employee_id')
            ->join('designation_masters as dm', 'dm.n_designation_id', 'em.n_designation_id')
            ->where('em.c_status', 'Y')
            ->select('em.n_employee_id', 'em.c_employee_name', 'dm.identifier')
            ->get();

        $products = ProductMaster::where('c_status', 'Y')->get();
        $franchises = StoreMaster::where('c_store_status', 'Y')->get();
        $states = State::where('status', 1)->get();
        $customers = CustomerMaster::orderBy('c_customer_name')->get();
        // $orderNo = SalesOrder::generateOrderNo();

        $viewmode = 'off';

        $user = Auth::user();

        $isFarmCareAdvisor = false;
        $farmCareAdvisorId = null;

        if ($user && $user->roles()->where('identifier', 'FCA')->exists()) {

            $isFarmCareAdvisor = true;

            // Fetch the corresponding employee
            $employee = EmployeeMaster::where('c_employee_email', $user->c_username)->first();
            // Or use another matching field if applicable

            if ($employee) {
                $farmCareAdvisorId = $employee->n_employee_id;
            }
        }

        return view('admin.sales.create', compact(
            'employees',
            'products',
            'franchises',
            'states',
            'viewmode',
            'customers',
            'farmCareAdvisorId',
            'isFarmCareAdvisor'
        ));
    }

    public function districtFilter(Request $request)
    {
        $districts = District::where('state_id', $request->state)->get();

        return response()->json(['districts' => $districts]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'd_date' => 'required|date',
            'c_order_no' => 'required|string|max:255',
            'farm_care_advisor_id' => 'nullable|integer|exists:employee_masters,n_employee_id',
            'n_customer_id' => 'required|exists:customer_masters,n_customer_id',
            'c_customer_name' => 'required|exists:customer_masters,c_customer_name',
            'c_customer_email' => 'nullable|email|max:255',
            'c_customer_address' => 'nullable|string|max:1000',
            'n_customer_mobile' => 'required|digits_between:10,15',
            'n_state_id' => 'required|integer|exists:states,n_state_id',
            'n_district_id' => 'required|integer|exists:districts,id',
            'nearest_franchise_id' => 'required|integer|exists:store_masters,n_store_id',
            'c_mode_of_payment' => 'required',

            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer',
            'products.*.product_price' => 'required|numeric',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.product_total' => 'required|numeric',
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        /* if ($validator->fails()) {
            dd($validator->errors()->toArray());
        } */

        $validated = $validator->validated();

        $user = Auth::user();
        $customer = CustomerMaster::findOrFail(
            $validated['n_customer_id']
        );

        if ($user->roles()->where('identifier', 'FCA')->exists()) {

            $employee = EmployeeMaster::where('c_employee_email', $user->c_username)->first();

            if ($employee) {
                $validated['farm_care_advisor_id'] = $employee->n_employee_id;
            }
        }
        // DB::beginTransaction();
        // dd($request);
        try {

            $imageName = null;

            if ($request->hasFile('payment_image')) {

                $image = $request->file('payment_image');

                $uploadPath = public_path('uploads/payment_images');

                // Create directory if it doesn't exist
                if (! is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate unique filename
                $imageName = uniqid().'.'.$image->getClientOriginalExtension();

                // Upload image
                $image->move($uploadPath, $imageName);

            }

            $order = [
                'c_order_no' => $validated['c_order_no'],
                'd_date' => $validated['d_date'],
                'farm_care_advisor_id' => $validated['farm_care_advisor_id'],
                'n_customer_id' => $customer->n_customer_id,
                'c_customer_name' => $validated['c_customer_name'],
                'c_customer_email' => $validated['c_customer_email'],
                'c_customer_address' => $validated['c_customer_address'],
                'n_customer_mobile' => $validated['n_customer_mobile'],
                'n_state_id' => $validated['n_state_id'],
                'n_district_id' => $validated['n_district_id'],
                'c_mode_of_payment' => $validated['c_mode_of_payment'],
                'nearest_franchise_id' => $validated['nearest_franchise_id'],
                'payment_image' => $imageName,
            ];

            if ($request->filled('id')) {

                $id = $request->id;
                // UPDATE

                $salesOrder = SalesOrder::where('n_sl_no', $id);

                $salesOrder->update($order);

                // Delete old items
                OrderProduct::where('n_order_id', $id)->delete();

                // Insert updated items
                if (isset($validated['products'])) {

                    foreach ($validated['products'] as $product) {

                        try {

                            $productData = OrderProduct::create([
                                'n_order_id' => $id,
                                'product_id' => $product['product_id'],
                                'product_price' => $product['product_price'],
                                'qty' => $product['qty'],
                                'product_total' => $product['product_total'],
                            ]);

                        } catch (\Exception $e) {
                            dd($e->getMessage());
                        }

                    }
                }

                $message = 'Sales updated successfully.';

            } else {

                // INSERT

                $salesOrder = SalesOrder::create($order);
                // dd($salesOrder);

                if (isset($validated['products'])) {
                    foreach ($validated['products'] as $product) {
                        try {
                            $productData = OrderProduct::create([
                                'n_order_id' => $salesOrder->n_sl_no,
                                'product_id' => $product['product_id'],
                                'product_price' => $product['product_price'],
                                'qty' => $product['qty'],
                                'product_total' => $product['product_total'],
                            ]);
                        } catch (\Exception $e) {
                            dd($e->getMessage());
                        }
                    }
                }
                $message = 'Sales created successfully.';
            }

            // DB::commit();

            return redirect()
                ->route('admin.sales.index')
                ->with('success', $message);

        } catch (\Exception $e) {

            // DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    // public function show(Request $request, $id)
    // {
    //     $id = Crypt::decryptString($id);
    //     $employees = EmployeeMaster::where('c_status', 'Y')->get();
    //     $products = ProductMaster::where('c_status', 'Y')->get();

    //     $sale = SalesOrder::with([
    //         'orderProducts',
    //         'customer',
    //     ])->findOrFail($id);
    //     // dd($sale);
    //     $states = State::with('districts')->where('status', '1')->get();
    //     $customers = CustomerMaster::orderBy('c_customer_name')->get();
    //     $franchises = StoreMaster::where('c_store_status', 'Y')->get();
    //     $viewmode = 'on';
    //     $user = Admin::join('model_has_roles as mr', 'mr.model_id', 'admins.n_role_id')
    //         ->join('roles', 'roles.id', 'mr.role_id')
    //         ->where('admins.n_role_id', Auth::user()->n_role_id)->first();

    //     $farmCareAdvisorId = null;
    //     $isFarmCareAdvisor = false;

    //     $designation = DesignationMaster::where(
    //         'n_designation_id',
    //         Auth::user()->n_designation_id
    //     )->first();

    //     if ($designation && $designation->identifier == 'FCA') {

    //         $isFarmCareAdvisor = true;
    //         $farmCareAdvisorId = Auth::user()->n_employee_id;

    //     }

    //     return view('admin.sales.create', compact('sale', 'employees', 'products', 'states', 'franchises', 'viewmode', 'user', 'farmCareAdvisorId', 'customers',
    //         'isFarmCareAdvisor'));
    // }

    // public function approve(Request $request)
    // {
    //     $request->validate([
    //         'status' => 'required',
    //         'remarks' => 'required',
    //     ]);

    //     $id = Crypt::decryptString($request->id);

    //     SalesApproval::updateOrCreate(
    //         ['sales_order_id' => $id],
    //         [
    //             'status' => $request->status,
    //             'remarks' => $request->remarks,
    //             'approved_by' => auth()->user()->n_role_id,
    //             'approved_at' => now(),
    //         ]
    //     );

    //     return redirect()->back()->with('success', 'Approval completed successfully.');
    // }

    public function show(Request $request, $id)
    {
        $id = Crypt::decryptString($id);

        $employees = EmployeeMaster::where('c_status', 'Y')->get();
        $products = ProductMaster::where('c_status', 'Y')->get();

        $sale = SalesOrder::with([
            'orderProducts',
            'customer',
        ])->findOrFail($id);

        // --------------------------------------------------
        // FCA midnight restriction
        // --------------------------------------------------
        if (! $this->isFcaToday($sale)) {
            abort(403, 'FCA cannot view this Sales Order after midnight.');
        }

        $states = State::with('districts')
            ->where('status', '1')
            ->get();

        $customers = CustomerMaster::orderBy('c_customer_name')->get();

        $franchises = StoreMaster::where('c_store_status', 'Y')->get();

        $viewmode = 'on';

        $user = Admin::join(
            'model_has_roles as mr',
            'mr.model_id',
            'admins.n_role_id'
        )
            ->join('roles', 'roles.id', 'mr.role_id')
            ->where('admins.n_role_id', Auth::user()->n_role_id)
            ->first();

        $farmCareAdvisorId = null;
        $isFarmCareAdvisor = false;

        $designation = DesignationMaster::where(
            'n_designation_id',
            Auth::user()->n_designation_id
        )->first();

        if ($designation && $designation->identifier == 'FCA') {

            $isFarmCareAdvisor = true;
            $farmCareAdvisorId = Auth::user()->n_employee_id;
        }

        return view(
            'admin.sales.create',
            compact(
                'sale',
                'employees',
                'products',
                'states',
                'franchises',
                'viewmode',
                'user',
                'farmCareAdvisorId',
                'customers',
                'isFarmCareAdvisor'
            )
        );
    }

    // public function edit(Request $request, $id)
    // {
    //     $id = Crypt::decryptString($id);
    //     $employees = EmployeeMaster::where('c_status', 'Y')->get();
    //     $products = ProductMaster::where('c_status', 'Y')->get();
    //     $sale = SalesOrder::with([
    //         'orderProducts',
    //         'customer',
    //     ])->findOrFail($id);
    //     $customers = CustomerMaster::orderBy('c_customer_name')->get();
    //     $states = State::with('districts')->where('status', '1')->get();
    //     $franchises = StoreMaster::where('c_store_status', 'Y')->get();
    //     $viewmode = 'off';
    //     $user = Auth::user();
    //     $sale = SalesOrder::with([
    //         'orderProducts',
    //         'customer',
    //     ])->findOrFail($id);

    //     $farmCareAdvisorId = null;
    //     $isFarmCareAdvisor = false;

    //     $designation = DesignationMaster::where(
    //         'n_designation_id',
    //         $user->n_designation_id
    //     )->first();

    //     if ($designation && $designation->identifier == 'FCA') {

    //         $isFarmCareAdvisor = true;
    //         $farmCareAdvisorId = $user->n_employee_id;

    //     }

    //     return view('admin.sales.create', compact('sale', 'employees', 'products', 'states', 'franchises', 'viewmode', 'farmCareAdvisorId',
    //         'isFarmCareAdvisor', 'customers'));
    // }

    public function edit(Request $request, $id)
    {
        $id = Crypt::decryptString($id);

        $employees = EmployeeMaster::where('c_status', 'Y')->get();
        $products = ProductMaster::where('c_status', 'Y')->get();

        $sale = SalesOrder::with([
            'orderProducts',
            'customer',
        ])->findOrFail($id);

        // --------------------------------------------------
        // FCA midnight restriction
        // --------------------------------------------------
        if (! $this->isFcaToday($sale)) {
            abort(403, 'FCA cannot edit this Sales Order after midnight.');
        }

        $customers = CustomerMaster::orderBy('c_customer_name')->get();

        $states = State::with('districts')
            ->where('status', '1')
            ->get();

        $franchises = StoreMaster::where('c_store_status', 'Y')->get();

        $viewmode = 'off';

        $user = Auth::user();

        $farmCareAdvisorId = null;
        $isFarmCareAdvisor = false;

        $designation = DesignationMaster::where(
            'n_designation_id',
            $user->n_designation_id
        )->first();

        if ($designation && $designation->identifier == 'FCA') {

            $isFarmCareAdvisor = true;
            $farmCareAdvisorId = $user->n_employee_id;
        }

        return view(
            'admin.sales.create',
            compact(
                'sale',
                'employees',
                'products',
                'states',
                'franchises',
                'viewmode',
                'farmCareAdvisorId',
                'isFarmCareAdvisor',
                'customers'
            )
        );
    }

    // public function destroy(Request $request, $id)
    // {
    //     $id = Crypt::decryptString($id);
    //     $sale = SalesOrder::where('n_sl_no', $id);
    //     $sale->update(['deleted_at' => date('Y-m-d')]);

    //     return redirect()->route('admin.sales.index')->with('success', 'Sales entry deleted successfully.');
    // }

    public function destroy(Request $request, $id)
    {
        $id = Crypt::decryptString($id);

        $sale = SalesOrder::where('n_sl_no', $id)->firstOrFail();

        // FCA can delete only today's Sales Orders
        if (! $this->isFcaToday($sale)) {
            abort(403, 'FCA cannot delete this Sales Order after midnight.');
        }

        $sale->update([
            'deleted_at' => now(),
        ]);

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'Sales entry deleted successfully.');
    }

    public function franchiseFilter(Request $request)
    {
        $franchises = StoreMaster::where('c_store_status', 'Y')
            ->where('n_state_id', $request->state)
            ->where('n_district_id', $request->district)
            ->orderBy('c_store_name', 'ASC')
            ->get([
                'n_store_id',
                'c_store_name',
                'c_store_code',
            ]);

        return response()->json([
            'franchises' => $franchises,
        ]);
    }
}