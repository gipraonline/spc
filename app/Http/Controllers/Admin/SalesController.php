<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

use App\Jobs\ProcessSalesUpload;
use App\Jobs\ProcessReturnsUpload;
use App\Models\SalesOrder;
use App\Models\DesignationMaster;
use App\Models\EmployeeIncentive;
use App\Models\EmployeeMaster;
use App\Models\ProductMaster;
use App\Models\AdminSaleDraft;
use App\Models\AdminSaleUpload;
use App\Models\ReturnSaleDraft;
use App\Models\ReturnDraftUpload;
use App\Models\StoreMaster;
use App\Models\AdminSaleUnnormalizedLog;
use App\Models\OrderProduct;
use App\Models\State;
use App\Models\District;
use App\Models\SalesApproval;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Exports\SaleReturnsReportExport;
use App\Exports\IncentiveSalesReportExport;

class SalesController extends Controller
{
public function index(Request $request)
    {
        $query = SalesOrder::query();
        $query=$query->with('employee','franchise')->whereNull('deleted_at');
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

        // Date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->where('d_date','>=',$request->start_date,)
                ->where('d_date','<=',$request->end_date,);
        }

        // From date only
        elseif ($request->filled('start_date')) {
            $query->whereDate('d_date', '>=', $request->start_date);
        }

        // To date only
        elseif ($request->filled('end_date')) {
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
        | Page Display
        |--------------------------------------------------------------------------
        */
        $sales = $query
            ->orderBy('d_date', 'desc')
            ->paginate(20)
            ->withQueryString();
    //dd($sales);
        return view('admin.sales.index', compact('sales'));
    }


    public function create()
    {
        $employees = EmployeeMaster::where('n_designation_id','5')->where('c_status', 'Y')->get();
        $products = ProductMaster::where('c_status', 'Y')->get();
        $franchises = StoreMaster::where('c_store_status', 'Y')->get();
        $states=State::where('status', '1')->get();
        $viewmode="off";
       // $districts=District::where('status', '1')->get();

        return view('admin.sales.create', compact('employees', 'products','franchises','states','viewmode'));
    }


    public function districtFilter(Request $request){
        $districts=District::where('state_id',$request->state)->get();
        return response()->json(['districts'=>$districts]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'd_date' => 'required|date',
            'c_bill_no' => 'required|string|max:255',
            'farm_care_advisor_id' => 'required|integer|exists:employee_masters,n_employee_id',
            'c_customer_name' => 'required|string|max:255',
            'c_customer_email' => 'nullable|email|max:255',
            'c_customer_address' => 'nullable|string|max:1000',
            'n_customer_mobile' => 'required|digits_between:10,15',
            'n_state_id' => 'required|integer|exists:states,n_state_id',
            'n_district_id' => 'required|integer|exists:districts,id',
            'nearest_franchise_id' => 'required|integer|exists:store_masters,n_store_id',
            'payment_status' => 'required',
            'delivery_status' => 'required',
            'c_mode_of_payment' => 'required',

            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer',
            'products.*.product_price' => 'required|numeric',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.product_total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {


            $order = [
                'c_bill_no' => $validated['c_bill_no'],
                'd_date' => $validated['d_date'],
                'farm_care_advisor_id' => $validated['farm_care_advisor_id'],
                'c_customer_name' => $validated['c_customer_name'],
                'c_customer_email' => $validated['c_customer_email'],
                'c_customer_address' => $validated['c_customer_address'],
                'n_customer_mobile' => $validated['n_customer_mobile'],
                'n_state_id' => $validated['n_state_id'],
                'n_district_id' => $validated['n_district_id'],
                'c_mode_of_payment' => $validated['c_mode_of_payment'],
                'nearest_franchise_id' => $validated['nearest_franchise_id'],
                'payment_status' => $validated['payment_status'],
                'delivery_status' => $validated['delivery_status'],
            ];

            if ($request->filled('id')) {

                $id=$request->id;
                // UPDATE

                $salesOrder = SalesOrder::where('n_sl_no',$id);

                $salesOrder->update($order);

                // Delete old items
                OrderProduct::where('n_order_id', $id)->delete();

                // Insert updated items
                if(isset($validated['products'])){

                    foreach($validated['products'] as $product) {

                        try {

                            $productData=OrderProduct::create([
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

            $message = 'Sales order updated successfully.';

        } else {

            // INSERT
            $salesOrder=SalesOrder::create($order);

            if(isset($validated['products'])){
                foreach($validated['products'] as $product) {
                    try {
                        $productData=OrderProduct::create([
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
            $message = 'Sales order created successfully.';
        }
           DB::commit();

            return redirect()
                ->route('admin.salesorders.index')
                ->with('Success', 'Sales entry created successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    public function show(Request $request,$id)
    {
        $id = Crypt::decryptString($id);
        $employees = EmployeeMaster::where('c_status', 'Y')->get();
        $products = ProductMaster::where('c_status', 'Y')->get();
        $sale = SalesOrder::with('orderProducts')->find($id);
        $states=State::with('districts')->where('status', '1')->get();
        $franchises = StoreMaster::where('c_store_status', 'Y')->get();
        $viewmode='on';
        return view('admin.sales.create', compact('sale','employees','products','states','franchises','viewmode'));
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


    public function edit(Request $request,$id)
    {
        $id = Crypt::decryptString($id);
        $employees = EmployeeMaster::where('c_status', 'Y')->get();
        $products = ProductMaster::where('c_status', 'Y')->get();
        $sale = SalesOrder::with('orderProducts')->find($id);
        $states=State::with('districts')->where('status', '1')->get();
        $franchises = StoreMaster::where('c_store_status', 'Y')->get();
        $viewmode='off';

        return view('admin.sales.create', compact('sale','employees','products','states','franchises','viewmode'));
    }



    public function destroy(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $sale=SalesOrder::where('n_sl_no',$id);
        $sale->update(['deleted_at'=>date('Y-m-d')]);
        return redirect()->route('admin.salesorders.index')->with('success', 'Sales entry deleted successfully.');
    }



}
