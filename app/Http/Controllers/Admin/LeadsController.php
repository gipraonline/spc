<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;



use App\Models\CustomerMaster;
use App\Models\Lead;
use App\Models\State;
use App\Models\District;
use App\Models\EmployeeMaster;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use DB;

class LeadsController extends Controller
{
    public function index(Request $request)
    {
        $leads=Lead::paginate(20);
        return view('admin.leads.index',compact('leads'));
    }

    public function create()
    {

        $employees = EmployeeMaster::where('n_designation_id', 5)
            ->where('c_status', 'Y')
            ->get();
        $states=State::where('status', '1')->get();
        $lead=new Lead;
        return view('admin.leads.create',compact('states','lead'));
    }

    public function districtFilter(Request $request){
        $districts=District::where('state_id',$request->state)->get();
        return response()->json(['districts'=>$districts]);
    }


    public function existingCustomer(Request $request)
    {
        $mobile = $request->mobile;

        $customer = CustomerMaster::where('n_mobile', $mobile)->first();

        if ($customer) {
            return response()->json([
                'status' => true,
                'customer' => $customer
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Customer not found'
        ]);
    }



    public function store(Request $request)
    {
        $id=isset($request->id) ? $request->id : '';
        $request->validate([
            'c_customer_type' => 'required|in:new,existing',
            'c_customer_name' => 'required|string|max:255',
            'n_mobile' => 'required|digits:10',
            'c_email' => 'nullable|email|max:255',
            'c_address' => 'nullable|string',

            'n_state_id' => 'nullable|exists:states,n_state_id',
            'n_district_id' => 'nullable|exists:districts,id',

            'd_visit_date' => 'required|date',

            'c_lead_status' => 'nullable|string|max:100',
            'd_expected_availability_date' => 'nullable|date',

            'next_followup_date' => 'nullable|date',
            'next_followup_time' => 'nullable',
            'followup_type' => 'nullable|string|max:100',

            'priority' => 'required|in:Low,Medium,High,Urgent',

            'remarks' => 'nullable|string',
        ]);

        $data = [

            'c_customer_type' => $request->c_customer_type,
            'c_customer_name' => $request->c_customer_name,
            'n_mobile' => $request->n_mobile,
            'c_email' => $request->c_email,
            'c_address' => $request->c_address,

            'n_state_id' => $request->n_state_id,
            'n_district_id' => $request->n_district_id,

            'd_visit_date' => $request->d_visit_date,

            'c_lead_status' => $request->c_lead_status,
            'd_expected_availability_date' => $request->d_expected_availability_date,

            'next_followup_date' => $request->next_followup_date,
            'next_followup_time' => $request->next_followup_time,
            'followup_type' => $request->followup_type,

            'priority' => $request->priority,
            'remarks' => $request->remarks,

            'updated_by' => Auth::user()->n_role_id,
        ];

        if ($id == null) {

            // Create
            $data['created_by'] = Auth::user()->n_role_id;

            Lead::create($data);

            return redirect()
                ->route('admin.leads.index')
                ->with('success', 'Lead created successfully.');

        } else {

            // Update
            $lead = Lead::findOrFail($id);

            $lead->update($data);

            return redirect()
                ->route('admin.leads.index')
                ->with('success', 'Lead updated successfully.');
        }


    }





    public function edit(Request $request,$id)
    {

        return view('admin.leads.create', compact('sale','employees','products','states','franchises','viewmode'));
    }



    public function destroy(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $sale=Leads::where('n_sl_no',$id);
        $sale->update(['deleted_at'=>date('Y-m-d')]);
        return redirect()->route('admin.leads.index')->with('success', 'Leads entry deleted successfully.');
    }



}
