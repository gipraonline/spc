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
    /**
     * Roles (roles.identifier) that can see ALL leads.
     * ⚠️ Make sure these match the real values in your roles table:
     *    SELECT id, name, identifier FROM roles;
     */
    private const ALL_LEADS_ROLES = ['OFFICE_ADMIN', 'TL'];

    /**
     * Designations (designation_masters.identifier) listed in the "Sales Person" filter.
     * ⚠️ Make sure these match the real values:
     *    SELECT n_designation_id, identifier FROM designation_masters;
     */
    private const SALES_DESIGNATIONS = ['FCA', 'FCO', 'TC'];

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

    /**
     * Office Administrator and Team Lead can see every lead.
     */
    private function seesAllLeads(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->whereIn('identifier', self::ALL_LEADS_ROLES)
                ->exists();
    }

    /**
     * Base query limited to what the logged-in user may see.
     * - Office Administrator / Team Lead : all leads
     * - Everyone else (FCA, FCO, Telecaller): only leads they created
     */
    private function scopedLeads()
    {
        $query = Lead::query();

        if ($this->seesAllLeads()) {
            return $query;
        }

        $user = Auth::user();

        // Own entries, plus leads a manager recorded against this person
        return $query->where(function ($w) use ($user) {
            $w->where('created_by', $user->n_role_id);

            if ($user->n_employee_id) {
                $w->orWhere('n_fca_id', $user->n_employee_id);
            }
        });
    }

    private function fcaEmployees()
    {
        return Admin::join(
            'employee_masters as em',
            'em.n_employee_id',
            '=',
            'admins.n_employee_id'
        )
            ->join(
                'designation_masters as dm',
                'dm.n_designation_id',
                '=',
                'em.n_designation_id'
            )
            ->where('em.c_status', 'Y')
            ->where('dm.identifier', 'FCA')
            ->select(
                'em.n_employee_id',
                'em.c_employee_name',
                'dm.identifier'
            )
            ->get();
    }

    /**
     * All active sales people (FCA / FCO / Telecaller).
     * Used only for the "Sales Person" filter shown to Office Administrator / Team Lead.
     */
    private function salesPersons()
    {
        return Admin::join(
            'employee_masters as em',
            'em.n_employee_id',
            '=',
            'admins.n_employee_id'
        )
            ->join(
                'designation_masters as dm',
                'dm.n_designation_id',
                '=',
                'em.n_designation_id'
            )
            ->where('em.c_status', 'Y')
            ->whereIn('dm.identifier', self::SALES_DESIGNATIONS)
            ->select(
                'em.n_employee_id',
                'em.c_employee_name',
                'dm.identifier'
            )
            ->orderBy('em.c_employee_name')
            ->get();
    }

    private function currentUser()
    {
        return Admin::join('model_has_roles as mr', 'mr.model_id', 'admins.n_role_id')
            ->join('roles', 'roles.id', 'mr.role_id')
            ->where('admins.n_role_id', Auth::user()->n_role_id)
            ->first();
    }

    public function index(Request $request)
    {
        // Only the leads this user is allowed to see
        $query = $this->scopedLeads();

        // Statistics (based on the same visibility scope)
        $totalLeads = $this->scopedLeads()->count();

        $pendingFollowups = $this->scopedLeads()
            ->whereDate('next_followup_date', '<=', Carbon::today())
            ->whereNotIn('c_lead_status', ['Won', 'Lost'])
            ->count();

        $readyToBuy = $this->scopedLeads()
            ->whereIn('c_lead_status', ['Interested', 'Negotiation'])
            ->count();

        $newCustomers = $this->scopedLeads()
            ->where('c_customer_type', 'new')
            ->count();

        $canSeeAll = $this->seesAllLeads();

        // Search: Customer / Mobile
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('c_customer_name', 'like', '%'.$search.'%')
                    ->orWhere('n_mobile', 'like', '%'.$search.'%');
            });
        }

        // FCA (filter only narrows the already-scoped query)
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

        // Sales persons filter list (Office Administrator / Team Lead only)
        $employees = $canSeeAll ? $this->salesPersons() : collect();

        // Leads
        $leads = $query
            ->latest('n_lead_id')
            ->paginate(20)
            ->withQueryString();

        // User
        $user = $this->currentUser();

        return view(
            'admin.leads.index',
            compact(
                'leads',
                'user',
                'employees',
                'totalLeads',
                'pendingFollowups',
                'readyToBuy',
                'newCustomers',
                'canSeeAll'
            )
        );
    }

    public function create()
    {
        $employees = $this->seesAllLeads() ? $this->salesPersons() : collect();

        $states = State::where('status', '1')->get();
        $lead = new Lead;
        $user = $this->currentUser();
        $viewMode = 'Off';
        $canSeeAll = $this->seesAllLeads();

        return view('admin.leads.create', compact('states', 'lead', 'user', 'viewMode', 'employees', 'canSeeAll'));
    }

    public function show(Request $request, $id)
    {
        $employees = $this->seesAllLeads() ? $this->salesPersons() : collect();

        $id = Crypt::decryptString($id);

        // Scoped: a user cannot open someone else's lead by editing the URL
        $lead = $this->scopedLeads()->with('fca')->findOrFail($id);

        $states = State::where('status', 1)->get();
        $user = $this->currentUser();
        $viewMode = 'On';
        $canSeeAll = $this->seesAllLeads();

        return view('admin.leads.create', compact('lead', 'user', 'states', 'viewMode', 'employees', 'canSeeAll'));
    }

    public function edit(Request $request, $id)
    {
        $employees = $this->seesAllLeads() ? $this->salesPersons() : collect();

        $id = Crypt::decryptString($id);

        // Scoped: a user cannot edit someone else's lead
        $lead = $this->scopedLeads()->with('fca')->findOrFail($id);

        $states = State::where('status', 1)->get();
        $user = $this->currentUser();
        $viewMode = 'Off';
        $canSeeAll = $this->seesAllLeads();

        return view('admin.leads.create', compact('lead', 'user', 'states', 'viewMode', 'employees', 'canSeeAll'));
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

            // Office Administrator / Team Lead must choose a Sales Person
            'n_fca_id' => $this->seesAllLeads()
                ? 'required|exists:employee_masters,n_employee_id'
                : 'nullable',
        ], [
            'n_fca_id.required' => 'Please select a Sales Person.',
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

        // Office Administrator / Team Lead choose the Sales Person.
        // FCA / FCO / Telecaller are always recorded against themselves.
        $data['n_fca_id'] = $this->seesAllLeads() && $request->filled('n_fca_id')
            ? $request->n_fca_id
            : Auth::user()->n_employee_id;

        if ($id == null) {

            // Create
            $data['created_by'] = Auth::user()->n_role_id;

            Lead::create($data);

            return redirect()
                ->route('admin.leads.index')
                ->with('success', 'Lead created successfully.');

        } else {

            // Update (scoped: only leads this user can see)
            $lead = $this->scopedLeads()->findOrFail($id);

            // Only managers may change the Sales Person on edit
            if (! $this->seesAllLeads()) {
                unset($data['n_fca_id']);
            }

            $lead->update($data);

            return redirect()
                ->route('admin.leads.index')
                ->with('success', 'Lead updated successfully.');
        }

    }

    public function destroy(Lead $lead)
    {
        // Block deleting leads outside the user's visibility
        abort_unless(
            $this->scopedLeads()->whereKey($lead->getKey())->exists(),
            403
        );

        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Leads entry deleted successfully.');
    }
}