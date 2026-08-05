<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerMaster;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
public function index()
{

    $query = CustomerMaster::query();

    $search = session('customer_search');
    $status = session('customer_status');

    // Search by Customer Code, Name or Mobile
    if (!empty($search)) {

        $query->where(function ($q) use ($search) {

            $q->where('c_customer_code', 'LIKE', "%{$search}%")
              ->orWhere('c_customer_name', 'LIKE', "%{$search}%")
              ->orWhere('n_mobile', 'LIKE', "%{$search}%")
              ->orWhere('n_whatsapp', 'LIKE', "%{$search}%");

        });
    }

    // Filter by Status
    if (!empty($status)) {
        $query->where('c_status', $status);
    }

    $customers = $query
                    ->orderBy('n_customer_id', 'desc')
                    ->paginate(10);

    return view('admin.customers.index', compact('customers'));
}

    public function create()
{
     $states = State::where('status', 1)
        ->orderBy('name')
        ->get();
    return view('admin.customers.create', compact('states'));
}

    public function store(Request $request)
{
    $validated = $request->validate([

        'c_customer_code' => [
            'required',
            'string',
            'max:20',
            'regex:/^[A-Za-z0-9_-]+$/',
            'unique:customer_masters,c_customer_code',
        ],

        'c_customer_name' => 'required|string|max:255',

        'n_mobile' => [
            'required',
            'regex:/^[6-9]\d{9}$/',
            'unique:customer_masters,n_mobile',
        ],

        'n_whatsapp' => [
            'nullable',
            'regex:/^[6-9]\d{9}$/',
        ],

        'c_email' => [
            'nullable',
            'email',
            'max:255',
            'unique:customer_masters,c_email',
        ],

        'c_address' => 'nullable|string',

        'c_district' => 'nullable|string|max:255',

        'c_state' => 'nullable|string|max:255',

        'c_pincode' => 'nullable|digits:6',

        'c_status' => 'required|in:Y,N',

    ], [

        'c_customer_code.required' => 'Customer Code is required.',
        'c_customer_code.unique' => 'Customer Code already exists.',
        'c_customer_code.regex' => 'Customer Code may contain only letters, numbers, hyphens and underscores.',

        'c_customer_name.required' => 'Customer Name is required.',

        'n_mobile.required' => 'Mobile Number is required.',
        'n_mobile.regex' => 'Please enter a valid 10-digit mobile number.',
        'n_mobile.unique' => 'Mobile Number already exists.',

        'n_whatsapp.regex' => 'Please enter a valid WhatsApp number.',

        'c_email.email' => 'Please enter a valid email address.',
        'c_email.unique' => 'Email already exists.',

        'c_pincode.digits' => 'Pincode should be 6 digits.',

        'c_status.required' => 'Please select customer status.',

    ]);

    DB::beginTransaction();

    try {

        CustomerMaster::create([

            'c_customer_code' => $validated['c_customer_code'],

            'c_customer_name' => $validated['c_customer_name'],

            'n_mobile' => $validated['n_mobile'],

            'n_whatsapp' => $validated['n_whatsapp'] ?? null,

            'c_email' => $validated['c_email'] ?? null,

            'c_address' => $validated['c_address'] ?? null,

            'c_district' => $validated['c_district'] ?? null,

            'c_state' => $validated['c_state'] ?? null,

            'c_pincode' => $validated['c_pincode'] ?? null,

            'c_status' => $validated['c_status'],

            'created_by' => auth()->id(),

        ]);

        DB::commit();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }

    }

    public function edit(CustomerMaster $customer)
{
    $states = State::where('status', 1)
        ->orderBy('name')
        ->get();

    $selectedState = State::where('name', $customer->c_state)->first();

    $districts = [];

    if ($selectedState) {
        $districts = District::where('state_id', $selectedState->n_state_id)
            ->orderBy('district_name')
            ->get();
    }

    return view('admin.customers.edit', compact(
        'customer',
        'states',
        'districts'
    ));
}

   public function update(Request $request, CustomerMaster $customer)
{
    $validated = $request->validate([

        'c_customer_name' => 'required|string|max:255',

        'n_mobile' => [
            'required',
            'regex:/^[6-9]\d{9}$/',
            'unique:customer_masters,n_mobile,' . $customer->n_customer_id . ',n_customer_id',
        ],

        'n_whatsapp' => [
            'nullable',
            'regex:/^[6-9]\d{9}$/',
        ],

        'c_email' => [
            'nullable',
            'email',
            'max:255',
            'unique:customer_masters,c_email,' . $customer->n_customer_id . ',n_customer_id',
        ],

        'c_address' => 'nullable|string',

        'c_district' => 'nullable|string|max:255',

        'c_state' => 'nullable|string|max:255',

        'c_pincode' => 'nullable|digits:6',

        'c_status' => 'required|in:Y,N',

    ], [

        'n_mobile.required' => 'Mobile Number is required.',
        'n_mobile.regex' => 'Please enter a valid 10-digit mobile number.',
        'n_mobile.unique' => 'Mobile Number already exists.',

        'n_whatsapp.regex' => 'Please enter a valid WhatsApp Number.',

        'c_email.email' => 'Please enter a valid email address.',
        'c_email.unique' => 'Email already exists.',

        'c_pincode.digits' => 'Pincode should be 6 digits.',

    ]);

    DB::beginTransaction();

    try {

        $customer->update([

            'c_customer_name' => $validated['c_customer_name'],

            'n_mobile' => $validated['n_mobile'],

            'n_whatsapp' => $validated['n_whatsapp'] ?? null,

            'c_email' => $validated['c_email'] ?? null,

            'c_address' => $validated['c_address'] ?? null,

            'c_district' => $validated['c_district'] ?? null,

            'c_state' => $validated['c_state'] ?? null,

            'c_pincode' => $validated['c_pincode'] ?? null,

            'c_status' => $validated['c_status'],

        ]);

        DB::commit();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function destroy(CustomerMaster $customer)
{
    $customer->update([
        'c_status' => 'D',
    ]);

    $customer->delete();

    return redirect()
        ->route('admin.customers.index')
        ->with('success', 'Customer deleted successfully.');
}

    public function search(Request $request)
{
    
    session([
        'customer_search' => $request->customer_search,
        'customer_status' => $request->c_status,
    ]);

    return redirect()->route('admin.customers.index');
}

    public function clearSearch()
{
    session()->forget([
        'customer_search',
        'customer_status',
    ]);

    return redirect()->route('admin.customers.index');
}
public function getDistricts($stateId)
{
    return District::where('state_id', $stateId)
        ->orderBy('district_name')
        ->get(['id', 'district_name']);
}
}