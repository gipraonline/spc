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
use App\Models\SalesOrderFollowup;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{

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
                /*
                |--------------------------------------------------------------------------
                | Base Sales Order Query
                |--------------------------------------------------------------------------
                */

                $query = SalesOrder::with(
                    'employee',
                    'franchise',
                    'customer'
                )
                ->whereNull('sales_orders.deleted_at');


                /*
                |--------------------------------------------------------------------------
                | Logged-in User
                |--------------------------------------------------------------------------
                */

                $user = Auth::user();


                /*
                |--------------------------------------------------------------------------
                | Farm Care Advisor Access
                |--------------------------------------------------------------------------
                */

                $isFarmCareAdvisor = $this->isFca();

                if ($isFarmCareAdvisor) {

                    $employeeId = $user->n_employee_id;

                    $query->where(
                        'sales_orders.farm_care_advisor_id',
                        $employeeId
                    );

                    $query->whereDate(
                        'sales_orders.d_date',
                        today()
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Employee Search
                |--------------------------------------------------------------------------
                */

                if ($request->filled('search')) {

                    $search = trim($request->search);

                    $query->whereHas('employee', function ($q) use ($search) {

                        $q->where(function ($sub) use ($search) {

                            $sub->where(
                                'c_employee_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'c_employee_code',
                                'like',
                                "%{$search}%"
                            );

                        });

                    });
                }


                /*
                |--------------------------------------------------------------------------
                | From Date
                |--------------------------------------------------------------------------
                */

                if ($request->filled('start_date')) {

                    $query->whereDate(
                        'sales_orders.d_date',
                        '>=',
                        $request->start_date
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | To Date
                |--------------------------------------------------------------------------
                */

                if ($request->filled('end_date')) {

                    $query->whereDate(
                        'sales_orders.d_date',
                        '<=',
                        $request->end_date
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Payment Status
                |--------------------------------------------------------------------------
                */

                if ($request->filled('payment_status')) {

                    $query->where(
                        'sales_orders.payment_status',
                        $request->payment_status
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CURRENT ORDER STATUS
                |--------------------------------------------------------------------------
                |
                | Priority:
                |
                | 1. Latest follow-up status
                | 2. Latest approval status
                | 3. sales_orders.c_order_status
                |
                */

                $currentStatusSql = "
                    COALESCE(

                        (
                            SELECT NULLIF(TRIM(sof.c_order_status), '')
                            FROM sales_order_followups AS sof
                            WHERE sof.n_sale_id = sales_orders.n_sl_no
                            ORDER BY sof.created_at DESC, sof.n_followup_id DESC
                            LIMIT 1
                        ),

                        (
                            SELECT NULLIF(TRIM(sa.status), '')
                            FROM sales_approvals AS sa
                            WHERE sa.sales_order_id = sales_orders.n_sl_no
                            ORDER BY sa.created_at DESC, sa.id DESC
                            LIMIT 1
                        ),

                        sales_orders.c_order_status

                    )
                ";


                /*
                |--------------------------------------------------------------------------
                | Add Current Status to Result
                |--------------------------------------------------------------------------
                */

                $query->select('sales_orders.*');

                $query->addSelect(
                    DB::raw("$currentStatusSql AS current_order_status")
                );


                /*
                |--------------------------------------------------------------------------
                | Order Status Filter
                |--------------------------------------------------------------------------
                */

                if ($request->filled('order_status')) {

                    $orderStatus = trim($request->order_status);

                    $query->whereRaw(
                        "LOWER(TRIM($currentStatusSql)) = LOWER(TRIM(?))",
                        [$orderStatus]
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Export Excel
                |--------------------------------------------------------------------------
                */

                if ($request->export === 'excel') {

                    $sales = $query
                        ->orderBy(
                            'sales_orders.d_date',
                            'desc'
                        )
                        ->get();

                    return Excel::download(
                        new IncentiveSalesReportExport($sales),
                        'sales-report.xlsx'
                    );
                }

                /*
        |--------------------------------------------------------------------------
        | Total Counts by Current Order Status
        |--------------------------------------------------------------------------
        */

        $countQuery = SalesOrder::query()
            ->whereNull('sales_orders.deleted_at');

        /*
        | FCA restriction
        */
        if ($isFarmCareAdvisor) {

            $countQuery->where(
                'sales_orders.farm_care_advisor_id',
                $user->n_employee_id
            );

            $countQuery->whereDate(
                'sales_orders.d_date',
                today()
            );
        }


        /*
        | Get current status for each order
        */
        $statusCounts = $countQuery
            ->select(
                'sales_orders.n_sl_no',
                DB::raw("
                    COALESCE(

                        (
                            SELECT NULLIF(TRIM(sof.c_order_status), '')
                            FROM sales_order_followups AS sof
                            WHERE sof.n_sale_id = sales_orders.n_sl_no
                            ORDER BY sof.created_at DESC, sof.n_followup_id DESC
                            LIMIT 1
                        ),

                        (
                            SELECT NULLIF(TRIM(sa.status), '')
                            FROM sales_approvals AS sa
                            WHERE sa.sales_order_id = sales_orders.n_sl_no
                            ORDER BY sa.created_at DESC, sa.id DESC
                            LIMIT 1
                        ),

                        sales_orders.c_order_status

                    ) AS current_status
                ")
            )
            ->get()
            ->groupBy(function ($order) {
                return strtolower(trim($order->current_status ?? 'pending'));
            });


        /*
        |--------------------------------------------------------------------------
        | Status Counts
        |--------------------------------------------------------------------------
        */

        $totalSalesOrders = $statusCounts->flatten()->count();

        $pendingOrders = $statusCounts->get('pending', collect())->count();

        $completedOrders = $statusCounts->get('completed', collect())->count();

        $approvedOrders = $statusCounts->get('approved', collect())->count();

        $dispatchedOrders = $statusCounts->get('dispatched', collect())->count();

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $sales = $query
            ->orderBy(
                'sales_orders.created_at',
                'desc'
            )
            ->orderBy(
                'sales_orders.n_sl_no',
                'desc'
            )
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.sales.index',
            compact(
                'sales',
                'isFarmCareAdvisor',
                //'totalSalesOrders',
                'pendingOrders',
                'completedOrders',
                'approvedOrders',
                'dispatchedOrders'
            )
        );
    }


    public function create()
    {

        $employees = Admin::join('employee_masters as em', 'em.n_employee_id', 'admins.n_employee_id')
            ->join('designation_masters as dm', 'dm.n_designation_id', 'em.n_designation_id')
            ->where('em.c_status', 'Y')
            ->select('em.n_employee_id', 'em.c_employee_name', 'dm.identifier')
            ->groupBy(
                'em.n_employee_id',
                'em.c_employee_name',
                'dm.identifier'
            )
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
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'd_date' => 'required|date',

            'c_order_no' => [
                'required',
                'string',
                'max:100',
                'unique:sales_orders,c_order_no',
            ],
            'farm_care_advisor_id' => 'nullable|integer|exists:employee_masters,n_employee_id',

            'n_customer_id' => 'required|integer|exists:customer_masters,n_customer_id',

            'c_customer_name' => 'required|string|max:255',

            'c_customer_email' => 'nullable|email|max:255',

            'c_customer_address' => 'nullable|string|max:1000',

            'n_customer_mobile' => 'required|digits_between:10,15',

            'n_state_id' => 'required|integer|exists:states,n_state_id',

            'n_district_id' => 'required|integer|exists:districts,id',

            'nearest_franchise_id' => 'required',

            'c_mode_of_payment' => 'required|string',

            //Totals//
            'n_total_sales_amount' => 'nullable|numeric|min:0',
            'n_product_discount_total' => 'nullable|numeric|min:0',
            'n_total_gst' => 'nullable|numeric|min:0',
            'n_total_discount' => 'nullable|numeric|min:0',
            'n_net_sales_amount' => 'nullable|numeric|min:0',


            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_status' => 'required|in:pending,confirmed',

            'c_transaction_id' => [
                'nullable',
                'string',
                'max:255',
                'required_if:payment_status,confirmed',
            ],

            'payment_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Booklet Proof
            |--------------------------------------------------------------------------
            */

            'f_booklet_proof' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            'products' => 'required|array|min:1',

            'products.*.product_id' => 'required|integer',

            'products.*.product_price' => 'required|numeric|min:0',

            'products.*.qty' => 'required|integer|min:1',

            'products.*.product_total' => 'required|numeric|min:0',

            'products.*.discount' => 'nullable|numeric|min:0',

            'products.*.n_gst_percentage' => 'nullable|numeric|min:0',

            'products.*.gst_amount' => 'nullable|numeric|min:0',
            'messages' => [
                'payment_image.max' => 'Payment proof image must not exceed 5 MB.',
                'f_booklet_proof.max' => 'Booklet proof image must not exceed 5 MB.',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validation Failed
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Customer
        |--------------------------------------------------------------------------
        */

        $customer = CustomerMaster::findOrFail(
            $validated['n_customer_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Farm Care Advisor
        |--------------------------------------------------------------------------
        */

        if ($user->roles()->where('identifier', 'FCA')->exists()) {

            $employee = EmployeeMaster::where(
                'c_employee_email',
                $user->c_username
            )->first();

            if ($employee) {
                $validated['farm_care_advisor_id'] =
                    $employee->n_employee_id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check Existing Order
        |--------------------------------------------------------------------------
        */

        $existingOrder = null;

        if ($request->filled('id')) {

            $existingOrder = SalesOrder::where(
                'n_sl_no',
                $request->id
            )->first();

            if (! $existingOrder) {
                return back()
                    ->withInput()
                    ->with('error', 'Sales order not found.');
            }
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Existing Images
            |--------------------------------------------------------------------------
            */

            $paymentImageName = $existingOrder
                ? $existingOrder->payment_image
                : null;

            $bookletImageName = $existingOrder
                ? $existingOrder->booklet_image
                : null;

            /*
            |--------------------------------------------------------------------------
            | Payment Image Upload
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('payment_image')) {

                $image = $request->file('payment_image');

                $uploadPath = public_path(
                    'uploads/payment_images'
                );

                if (! is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $paymentImageName =
                    uniqid('payment_').'.'.
                    $image->getClientOriginalExtension();

                $image->move(
                    $uploadPath,
                    $paymentImageName
                );

                /*
                | Delete old image on update
                */

                if (
                    $existingOrder &&
                    $existingOrder->payment_image
                ) {

                    $oldFile = public_path(
                        'uploads/payment_images/'.
                        $existingOrder->payment_image
                    );

                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Booklet Proof Upload
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('f_booklet_proof')) {

                $image = $request->file('f_booklet_proof');

                $uploadPath = public_path(
                    'uploads/booklet_images'
                );

                if (! is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $bookletImageName =
                    uniqid('booklet_').'.'.
                    $image->getClientOriginalExtension();

                $image->move(
                    $uploadPath,
                    $bookletImageName
                );

                /*
                | Delete old booklet image on update
                */

                if (
                    $existingOrder &&
                    $existingOrder->booklet_image
                ) {

                    $oldFile = public_path(
                        'uploads/booklet_images/'.
                        $existingOrder->booklet_image
                    );

                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            if ($validated['payment_status'] === 'pending') {

                $transactionId = null;

                /*
                | New pending order should not have payment proof
                */

                if (! $existingOrder) {
                    $paymentImageName = null;
                }

            } else {

                $transactionId =
                    $validated['c_transaction_id'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | Order Data
            |--------------------------------------------------------------------------
            */

            // $orderData = [

            //     'c_order_no' => $validated['c_order_no'],

            //     'd_date' => $validated['d_date'],

            //     'farm_care_advisor_id' => $validated['farm_care_advisor_id'] ?? null,

            //     'n_customer_id' => $customer->n_customer_id,

            //     'c_customer_name' => $validated['c_customer_name'],

            //     'c_customer_email' => $validated['c_customer_email'] ?? null,

            //     'c_customer_address' => $validated['c_customer_address'] ?? null,

            //     'n_customer_mobile' => $validated['n_customer_mobile'],

            //     'n_state_id' => $validated['n_state_id'],

            //     'n_district_id' => $validated['n_district_id'],

            //     'c_mode_of_payment' => $validated['c_mode_of_payment'],

            //     'c_order_status' => 'pending',

            //     'nearest_franchise_id' => $validated['nearest_franchise_id'],

            //     /*
            //     | Payment
            //     */

            //     'payment_status' => $validated['payment_status'],

            //     'c_transaction_id' => $transactionId,

            //     'payment_image' => $paymentImageName,

            //     /*
            //     | Booklet
            //     */

            //     'booklet_image' => $bookletImageName,
            //     'booklet_image' => $bookletImageName,

            //     /*
            //     | Order Summary
            //     */

            //     'n_total_sales_amount' =>
            //         $validated['n_total_sales_amount'] ?? 0,

            //     'n_product_discount_total' =>
            //         $validated['n_product_discount_total'] ?? 0,

            //     'n_total_gst' =>
            //         $validated['n_total_gst'] ?? 0,

            //     'n_total_discount' =>
            //         $validated['n_total_discount'] ?? 0,

            //     'n_net_sales_amount' =>
            //         $validated['n_net_sales_amount'] ?? 0,

            // ];


            $orderData = [

                'c_order_no' => $validated['c_order_no'],

                'd_date' => $validated['d_date'],

                'farm_care_advisor_id' =>
                    $validated['farm_care_advisor_id'] ?? null,

                'n_customer_id' => $customer->n_customer_id,

                'c_customer_name' => $validated['c_customer_name'],

                'c_customer_email' =>
                    $validated['c_customer_email'] ?? null,

                'c_customer_address' =>
                    $validated['c_customer_address'] ?? null,

                'n_customer_mobile' =>
                    $validated['n_customer_mobile'],

                'n_state_id' =>
                    $validated['n_state_id'],

                'n_district_id' =>
                    $validated['n_district_id'],

                'c_mode_of_payment' =>
                    $validated['c_mode_of_payment'],

                // IMPORTANT
                'c_order_status' =>
                    $validated['c_order_status'] ?? 'Pending',

                'nearest_franchise_id' =>
                    $validated['nearest_franchise_id'],

                /*
                | Payment
                */

                'payment_status' =>
                    $validated['payment_status'],

                'c_transaction_id' =>
                    $transactionId,

                'payment_image' =>
                    $paymentImageName,

                /*
                | Booklet
                */

                'booklet_image' =>
                    $bookletImageName,

                /*
                | Order Summary
                */

                'n_total_sales_amount' =>
                    $validated['n_total_sales_amount'] ?? 0,

                'n_product_discount_total' =>
                    $validated['n_product_discount_total'] ?? 0,

                'n_total_gst' =>
                    $validated['n_total_gst'] ?? 0,

                'n_total_discount' =>
                    $validated['n_total_discount'] ?? 0,

                'n_net_sales_amount' =>
                    $validated['n_net_sales_amount'] ?? 0,
            ];

            /*
            |--------------------------------------------------------------------------
            | Update Existing Order
            |--------------------------------------------------------------------------
            */

            // if ($existingOrder) {

            //     $existingOrder->update($orderData);

            //     /*
            //     | Delete old products
            //     */

            //     OrderProduct::where(
            //         'n_order_id',
            //         $existingOrder->n_sl_no
            //     )->delete();

            //     /*
            //     | Insert products again
            //     */

            //     foreach ($validated['products'] as $product) {



            //            OrderProduct::create([

            //                 'n_order_id' => $existingOrder->n_sl_no,

            //                 'product_id' => $product['product_id'],

            //                 'n_hsn_code' => $product['n_hsn_code'] ?? null,

            //                 'product_price' => $product['product_price'],

            //                 'qty' => $product['qty'],

            //                 'c_unit' => $product['c_unit'] ?? null,

            //                 'discount' => $product['discount'] ?? 0,

            //                 'n_gst_percentage' =>
            //                     $product['n_gst_percentage'] ?? 0,

            //                 'gst_amount' =>
            //                     $product['gst_amount'] ?? 0,

            //                 'discounted_price' =>
            //                     $product['discounted_price'] ?? 0,

            //                 'product_total' =>
            //                     $product['product_total'],
            //         ]);
            //     }

            //     $message =
            //         'Sales order updated successfully.';

            // } else {

            //     /*
            //     |--------------------------------------------------------------------------
            //     | Create New Order
            //     |--------------------------------------------------------------------------
            //     */

            //     $salesOrder =
            //         SalesOrder::create($orderData);

            //     /*
            //     | Insert products
            //     */

            //     foreach ($validated['products'] as $product) {

            //         OrderProduct::create([

            //             'n_order_id' => $salesOrder->n_sl_no,

            //             'product_id' => $product['product_id'],

            //             'n_hsn_code' => $product['n_hsn_code'] ?? null,

            //             'product_price' => $product['product_price'],

            //             'qty' => $product['qty'],

            //             'c_unit' => $product['c_unit'] ?? null,

            //             'discount' => $product['discount'] ?? 0,

            //             'n_gst_percentage' =>
            //                 $product['n_gst_percentage'] ?? 0,

            //             'gst_amount' =>
            //                 $product['gst_amount'] ?? 0,

            //             'discounted_price' =>
            //                 $product['discounted_price'] ?? 0,

            //             'product_total' =>
            //                 $product['product_total'],
            //         ]);
            //     }

            //     $message =
            //         'Sales order created successfully.';
            // }

              if ($existingOrder) {

                /*
                |--------------------------------------------------------------------------
                | UPDATE EXISTING ORDER
                |--------------------------------------------------------------------------
                */

                $existingOrder->update($orderData);

                /*
                | Delete old products
                */

                OrderProduct::where(
                    'n_order_id',
                    $existingOrder->n_sl_no
                )->delete();

                /*
                | Insert products again
                */

                foreach ($validated['products'] as $product) {

                    OrderProduct::create([

                        'n_order_id' =>
                            $existingOrder->n_sl_no,

                        'product_id' =>
                            $product['product_id'],

                        'product_price' =>
                            $product['product_price'],

                        'qty' =>
                            $product['qty'],

                        'discount' =>
                            $product['discount'] ?? 0,

                        'n_gst_percentage' =>
                            $product['n_gst_percentage'] ?? 0,

                        'gst_amount' =>
                            $product['gst_amount'] ?? 0,

                        'product_total' =>
                            $product['product_total'] ?? 0,
                    ]);
                }

                $message = 'Sales order updated successfully.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | CREATE NEW ORDER
                |--------------------------------------------------------------------------
                */

                $salesOrder = SalesOrder::create($orderData);

                /*
                | Insert products
                */

                foreach ($validated['products'] as $product) {

                    OrderProduct::create([

                        // IMPORTANT: use new order ID
                        'n_order_id' =>
                            $salesOrder->n_sl_no,

                        'product_id' =>
                            $product['product_id'],

                        'product_price' =>
                            $product['product_price'],

                        'qty' =>
                            $product['qty'],

                        'discount' =>
                            $product['discount'] ?? 0,

                        'n_gst_percentage' =>
                            $product['n_gst_percentage'] ?? 0,

                        'gst_amount' =>
                            $product['gst_amount'] ?? 0,

                        'product_total' =>
                            $product['product_total'] ?? 0,
                    ]);
                }

                $message = 'Sales order created successfully.';
            }

            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('admin.salesorders.index')
                ->with('success', $message);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save sales order: '.
                    $e->getMessage()
                );
        }
    }

   public function storeFollowup(Request $request)
    {
        $request->validate([
            'n_sale_id' => 'required|exists:sales_orders,n_sl_no',
            'd_followup_date' => 'required|date',
            'c_order_status' => 'nullable|string|max:100',
            'remarks' => 'required|string',
        ]);


        $sale = SalesOrder::findOrFail($request->n_sale_id);


        SalesOrderFollowup::create([
            'n_sale_id' => $sale->n_sl_no,
            'd_followup_date' => $request->d_followup_date,
            'c_order_status' => $request->c_order_status,
            'remarks' => $request->remarks,
            'n_created_by' => Auth::id(),
        ]);


        // Optional: update current order status
        if ($request->filled('c_order_status')) {

            $sale->c_order_status = $request->c_order_status;

            $sale->save();
        }


        return redirect()
            ->back()
            ->with('success', 'Follow-up saved successfully.');
    }

    public function approve(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'remarks' => 'required',
        ]);

        $id = Crypt::decryptString($request->id);

        SalesApproval::updateOrCreate(
            ['sales_order_id' => $id],
            [
                'status' => $request->status,
                'remarks' => $request->remarks,
                'approved_by' => auth()->user()->n_role_id,
                'approved_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Approval completed successfully.');
    }

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
            ->route('admin.salesorders.index')
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
