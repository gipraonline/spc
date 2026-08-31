<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CustomerMaster;
use App\Models\Lead;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    private function isFca(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->where('identifier', 'FCA')
                ->exists();
    }

    private function isFcaToday(Lead $lead): bool
    {
        return ! $this->isFca()
            || (
                (int) $lead->n_fca_id === (int) Auth::user()->n_employee_id
                && Carbon::parse($lead->d_visit_date)->isToday()
            );
    }

    public function index(Request $request)
    {
        $query = Lead::query();

        // FCA: only own leads created/assigned for today
        if ($this->isFca()) {
            $query->where('n_fca_id', Auth::user()->n_employee_id)
                ->whereDate('d_visit_date', Carbon::today());
        }

        // Search: Customer / Mobile
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('c_customer_name', 'like', '%'.$search.'%')
                    ->orWhere('n_mobile', 'like', '%'.$search.'%');
            });
        }

        // FCA

        if ($request->filled('n_fca_id')) {

            $query->where('n_fca_id', $request->n_fca_id);
        }

        // From Date
        if ($request->filled('from_date')) {

            $query->whereDate(
                'd_visit_date',
                '>=',
                $request->from_date
            );
        }

        // To Date
        if ($request->filled('to_date')) {

            $query->whereDate(
                'd_visit_date',
                '<=',
                $request->to_date
            );
        }

        // Status
        if ($request->filled('status')) {

            $query->where(
                'c_lead_status',
                $request->status
            );
        }

        // Employees
        $employees = Admin::join('employee_masters as em', 'em.n_employee_id', 'admins.n_employee_id')
            ->join('designation_masters as dm', 'dm.n_designation_id', 'em.n_designation_id')
            ->where('em.c_status', 'Y')
            ->select('em.n_employee_id', 'em.c_employee_name', 'dm.identifier')
            ->get();

        // Leads
        $leads = $query
            ->latest('n_lead_id')
            ->paginate(20)
            ->withQueryString();

        // User
        $user = Admin::join(
            'model_has_roles as mr',
            'mr.model_id',
            '=',
            'admins.n_role_id'
        )
            ->join(
                'roles',
                'roles.id',
                '=',
                'mr.role_id'
            )
            ->where(
                'admins.n_role_id',
                Auth::user()->n_role_id
            )
            ->first();

        return view(
            'admin.leads.index',
            compact('leads', 'user', 'employees')
        );
    }

    public function create()
    {
        $employees = Admin::join('employee_masters as em', 'em.n_employee_id', 'admins.n_employee_id')
            ->join('designation_masters as dm', 'dm.n_designation_id', 'em.n_designation_id')
            ->where('em.c_status', 'Y')
            ->select('em.n_employee_id', 'em.c_employee_name', 'dm.identifier')
            ->get();

        $states = State::where('status', '1')->get();
        $lead = new Lead;
        $user = Admin::join('model_has_roles as mr', 'mr.model_id', 'admins.n_role_id')
            ->join('roles', 'roles.id', 'mr.role_id')
            ->where('admins.n_role_id', Auth::user()->n_role_id)->first();
        $viewMode = 'Off';

        return view('admin.leads.create', compact('states', 'lead', 'user', 'viewMode', 'employees'));
    }

    public function show(Request $request, $id)
    {
        $employees = Admin::join('employee_masters as em', 'em.n_employee_id', 'admins.n_employee_id')
            ->join('designation_masters as dm', 'dm.n_designation_id', 'em.n_designation_id')
            ->where('em.c_status', 'Y')
            ->select('em.n_employee_id', 'em.c_employee_name', 'dm.identifier')
            ->get();

        $id = Crypt::decryptString($id);
        $lead = Lead::with('fca')->findOrFail($id);
        $states = State::where('status', 1)->get();
        $user = Admin::join('model_has_roles as mr', 'mr.model_id', 'admins.n_role_id')
            ->join('roles', 'roles.id', 'mr.role_id')
            ->where('admins.n_role_id', Auth::user()->n_role_id)->first();
        $viewMode = 'On';

        return view('admin.leads.create', compact('lead', 'user', 'states', 'viewMode', 'employees'));
    }

    public function edit(Request $request, $id)
    {
        $employees = Admin::join('employee_masters as em', 'em.n_employee_id', 'admins.n_employee_id')
            ->join('designation_masters as dm', 'dm.n_designation_id', 'em.n_designation_id')
            ->where('em.c_status', 'Y')
            ->select('em.n_employee_id', 'em.c_employee_name', 'dm.identifier')
            ->get();

        $id = Crypt::decryptString($id);
        $lead = Lead::with('fca')->findOrFail($id);
        $states = State::where('status', 1)->get();
        $user = Admin::join('model_has_roles as mr', 'mr.model_id', 'admins.n_role_id')
            ->join('roles', 'roles.id', 'mr.role_id')
            ->where('admins.n_role_id', Auth::user()->n_role_id)->first();
        $viewMode = 'Off';

        return view('admin.leads.create', compact('lead', 'user', 'states', 'viewMode', 'employees'));

    }

    public function existingCustomer(Request $request)
    {
        $mobile = $request->mobile;

        $customer = CustomerMaster::where('n_mobile', $mobile)->first();

        if ($customer) {
            return response()->json([
                'status' => true,
                'customer' => $customer,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Customer not found',
        ]);
    }

    public function store(Request $request)
    {
        $id = isset($request->n_lead_id) ? $request->n_lead_id : '';
        $validator = Validator::make($request->all(), [
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

            /* 'next_followup_date' => 'nullable|date',
           'next_followup_time' => 'nullable',
           'followup_type' => 'nullable|string|max:100',
 */
            'priority' => 'required|in:Low,Medium,High,Urgent',

            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'n_fca_id' => isset($request->n_fca_id) ? $request->n_fca_id : Auth::user()->n_employee_id,
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

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Leads entry deleted successfully.');
    }
}
