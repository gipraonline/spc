<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerMaster;
use App\Models\District;
use App\Models\Lead;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // private function isFca(): bool
    // {
    //     return Auth::check()
    //         && Auth::user()->roles()
    //             ->where('identifier', 'FCA')
    //             ->exists();
    // }

    // public function index()
    // {
    //     $query = CustomerMaster::with(['state', 'district']);

    //     // Get search/filter values from session
    //     $search = session('customer_search');
    //     $status = session('customer_status');

    //     // Search by Customer Code, Name, Mobile or WhatsApp
    //     if (! empty($search)) {

    //         $query->where(function ($q) use ($search) {
    //             $q->where('c_customer_code', 'LIKE', "%{$search}%")
    //                 ->orWhere('c_customer_name', 'LIKE', "%{$search}%")
    //                 ->orWhere('n_mobile', 'LIKE', "%{$search}%")
    //                 ->orWhere('n_whatsapp', 'LIKE', "%{$search}%");
    //         });
    //     }

    //     // Filter by Status
    //     if (! empty($status)) {
    //         $query->where('c_status', $status);
    //     }

    //     $customers = $query
    //         ->orderBy('n_customer_id', 'desc')
    //         ->paginate(10);

    //     return view('admin.customers.index', compact('customers'));
    // }
    private function isFca(): bool
    {
        return Auth::check()
            && Auth::user()->roles()
                ->where('identifier', 'FCA')
                ->exists();
    }

//     public function index()
//     {
//         $isFarmCareAdvisor = $this->isFca();

//         $query = CustomerMaster::with(['state', 'district']);

//         $search = session('customer_search');
//         $status = session('customer_status');

//         /*
//         |--------------------------------------------------------------------------
//         | FCA restriction
//         |--------------------------------------------------------------------------
//         | FCA can see only customers whose lead:
//         | 1. Belongs to logged-in FCA
//         | 2. Has today's visit date
//         |--------------------------------------------------------------------------
//         */
//         if ($isFarmCareAdvisor) {

//             $todayMobiles = Lead::where(
//                 'n_fca_id',
//                 Auth::user()->n_employee_id
//             )
//                 ->whereDate('d_visit_date', Carbon::today())
//                 ->whereNotNull('n_mobile')
//                 ->pluck('n_mobile');

//             $query->whereIn('n_mobile', $todayMobiles);
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Search
//         |--------------------------------------------------------------------------
//         */
//         if (! empty($search)) {

//             $query->where(function ($q) use ($search, $isFarmCareAdvisor) {

//                 $q->where('c_customer_code', 'LIKE', "%{$search}%")
//                     ->orWhere('c_customer_name', 'LIKE', "%{$search}%");

//                 // Only non-FCA users can search by phone/WhatsApp
//                 if (! $isFarmCareAdvisor) {

//                     $q->orWhere('n_mobile', 'LIKE', "%{$search}%")
//                         ->orWhere('n_whatsapp', 'LIKE', "%{$search}%");
//                 }
//             });
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Status
//         |--------------------------------------------------------------------------
//         */
//         if (! empty($status)) {
//             $query->where('c_status', $status);
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Customers
//         |--------------------------------------------------------------------------
//         */
//         $customers = $query
//             ->orderBy('n_customer_id', 'desc')
//             ->paginate(10)
//             ->withQueryString();
// dd($customers);
//         return view('admin.customers.index', compact(
//             'customers',
//             'isFarmCareAdvisor'
//         ));
//     }

    public function index()
    {
        $isFarmCareAdvisor = $this->isFca();

        $query = CustomerMaster::with(['state', 'district']);

        $search = session('customer_search');
        $status = session('customer_status');

        /*
        |--------------------------------------------------------------------------
        | FCA restriction
        |--------------------------------------------------------------------------
        | FCA can see only customers added by the logged-in FCA
        */
        if ($isFarmCareAdvisor) {

            $query->where(
                'created_by',
                Auth::user()->n_employee_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if (!empty($search)) {

            $query->where(function ($q) use ($search, $isFarmCareAdvisor) {

                $q->where('c_customer_code', 'LIKE', "%{$search}%")
                    ->orWhere('c_customer_name', 'LIKE', "%{$search}%");

                // Only non-FCA users can search by phone/WhatsApp
                if (!$isFarmCareAdvisor) {
                    $q->orWhere('n_mobile', 'LIKE', "%{$search}%")
                        ->orWhere('n_whatsapp', 'LIKE', "%{$search}%");
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        if (!empty($status)) {
            $query->where('c_status', $status);
        }

        $customers = $query
            ->orderBy('n_customer_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact(
            'customers',
            'isFarmCareAdvisor'
        ));
    }

    public function create()
    {
        $states = State::where('status', 1)
            ->orderBy('name')
            ->get();
        $customerCode = CustomerMaster::generateCustomerCode();

        return view('admin.customers.create', compact('states', 'customerCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'c_customer_code' => [
                'nullable',
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

            'n_state_id' => 'nullable|exists:states,n_state_id',

            'n_district_id' => 'nullable|exists:districts,id',

            'c_pincode' => 'nullable|digits:6',

            'c_status' => 'required|in:Y,N',

        ], [
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

                'c_customer_code' => CustomerMaster::generateCustomerCode(),

                'c_customer_name' => $validated['c_customer_name'],

                'n_mobile' => $validated['n_mobile'],

                'n_whatsapp' => $validated['n_whatsapp'] ?? null,

                'c_email' => $validated['c_email'] ?? null,

                'c_address' => $validated['c_address'] ?? null,

                'n_state_id' => $validated['n_state_id'] ?? null,

                'n_district_id' => $validated['n_district_id'] ?? null,

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

        // $selectedState = State::where('name', $customer->c_state)->first();
        $districts = District::where(
            'state_id',
            $customer->n_state_id
        )
            ->orderBy('district_name')
            ->get();

        // $districts = [];

        // if ($selectedState) {
        //     $districts = District::where('state_id', $selectedState->n_state_id)
        //         ->orderBy('district_name')
        //         ->get();
        // }

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
                'unique:customer_masters,n_mobile,'.$customer->n_customer_id.',n_customer_id',
            ],

            'n_whatsapp' => [
                'nullable',
                'regex:/^[6-9]\d{9}$/',
            ],

            'c_email' => [
                'nullable',
                'email',
                'max:255',
                'unique:customer_masters,c_email,'.$customer->n_customer_id.',n_customer_id',
            ],

            'c_address' => 'nullable|string',

            'n_state_id' => 'nullable|exists:states,n_state_id',

            'n_district_id' => 'nullable|exists:districts,id',

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

                'n_state_id' => $validated['n_state_id'] ?? null,

                'n_district_id' => $validated['n_district_id'] ?? null,
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
