<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreMaster;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function search(Request $request)
    {
        session(['store_search' => $request->search,
        'store_state_id'     => $request->state_id,
        'store_district_id'  => $request->district_id,]);

        return redirect()->route('admin.franchises.index');
    }

    public function clearSearch()
    {
      session()->forget([
        'store_search',
        'store_state_id',
        'store_district_id',
    ]);
        return redirect()->route('admin.franchises.index');
    }

//        public function index(Request $request)
// {
//     $stores = StoreMaster::with(['state', 'district']);

//     $search = session('store_search');

//     if (!empty($search)) {

//         $stores->where(function ($query) use ($search) {
//             $query->where('c_store_code', 'LIKE', "%{$search}%")
//                   ->orWhere('c_store_name', 'LIKE', "%{$search}%");
//         });
//     }

//     $stores = $stores->paginate(15);

//     return view('admin.stores.index', compact('stores'));
// }

public function index(Request $request)
{
    $search = session('store_search');
    $stateId = session('store_state_id');
    $districtId = session('store_district_id');

    $stores = StoreMaster::with(['state', 'district']);

    // Search
    if (!empty($search)) {
        $stores->where(function ($query) use ($search) {
            $query->where('c_store_code', 'LIKE', "%{$search}%")
                  ->orWhere('c_store_name', 'LIKE', "%{$search}%");
        });
    }

    // State
    if (!empty($stateId)) {
        $stores->where('n_state_id', $stateId);
    }

    // District
    if (!empty($districtId)) {
        $stores->where('n_district_id', $districtId);
    }

    $stores = $stores
        ->orderBy('n_store_id', 'desc')
        ->paginate(15);

    // ALWAYS load states
    $states = State::where('status', 1)
        ->orderBy('name')
        ->get();

    // Load districts for selected state
    $districts = collect();

    if (!empty($stateId)) {
        $districts = District::where('state_id', $stateId)
            ->orderBy('district_name')
            ->get();
    }

    return view('admin.stores.index', compact(
        'stores',
        'states',
        'districts'
    ));
}
    public function create()
    {
        $states = State::where('status', 1)
                ->orderBy('name')
                ->get();
        return view('admin.stores.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'c_store_code' => 'required|string|max:30|regex:/^[A-Za-z0-9\s\-]+$/|unique:store_masters,c_store_code',
            'c_store_name' => 'required|string|max:100|regex:/^[A-Za-z0-9\s\-]+$/',
             'c_owner_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
            'c_store_address' => 'required|string|max:255',
            'n_state_id'       => 'required|integer',
            'n_district_id'    => 'required|integer',
            'c_store_email' => 'required|email:rfc,dns|max:100',
            'n_store_phone' => 'required|regex:/^[6-9]\d{9}$/',
            'c_store_status' => 'required|in:Y,N',
        ], ['c_store_code.required' => 'Store code is required',
            'c_store_code.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',
            'c_store_name.required' => 'Store name is required',
            'c_store_name.regex' => 'Special characters are not allowed.Use letters, numbers, spaces',
            'c_owner_name.required' => 'Owner name is required',

            'c_owner_name.regex' => 'Owner name can contain only letters, spaces and hyphens',
            'c_store_address.required' => 'Address is required',
            'n_state_id.required'      => 'Please select a state',
            'n_district_id.required'   => 'Please select a district',
            'c_store_email.email' => 'Enter a valid Email id',
            'n_store_phone.regex' => 'Enter a valid Phone number',
            ]);

        StoreMaster::create($validated);

        return redirect()->route('admin.franchises.index')->with('Success', 'Franchise created successfully');
    }

    public function show(StoreMaster $store)
    {
        $store->load('employees');

        return view('admin.stores.show', compact('store'));
    }

    public function edit(StoreMaster $franchise)
    {
       $states = State::where('status', 1)
        ->orderBy('name')
        ->get();

    $districts = District::where('state_id', $franchise->n_state_id)
        ->orderBy('district_name')
        ->get();
//       dd(
//     District::where('state_id', 98)->get()
// );
    return view('admin.stores.edit', compact(
        'franchise',
        'states',
        'districts'
    ));
    }

    public function update(Request $request, StoreMaster $franchise)
    {
        $validated = $request->validate([
            'c_store_code' => 'required|string|max:30|regex:/^[A-Za-z0-9\s\-]+$/|unique:store_masters,c_store_code,'.$franchise->n_store_id.',n_store_id',
            'c_store_name' => 'required|string|max:100|regex:/^[A-Za-z0-9\s\-]+$/',
            'c_owner_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
            'c_store_address' => 'nullable|string|max:255',
            'n_state_id'      => 'required|integer',
            'n_district_id'   => 'required|integer',
            'c_store_email' => 'nullable|email:rfc,dns|max:100',
            'n_store_phone' => 'nullable|regex:/^[6-9]\d{9}$/',
            'c_store_status' => 'required|in:Y,N',
        ], ['c_store_code.required' => 'Store code is required',
            'c_store_code.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',
            'c_store_name.required' => 'Store name is required',
            'c_store_name.regex' => 'Special characters are not allowed.Use letters, numbers, spaces',
            'c_owner_name.required' => 'Owner name is required',
            'c_owner_name.regex' => 'Owner name can contain only letters, spaces and hyphens',
            'c_store_address.required' => 'Address is required',
            'c_store_email.email' => 'Enter a valid Email id',
            'n_store_phone.regex' => 'Enter a valid Phone number',
            ]);

        $franchise->update($validated);

        return redirect()->route('admin.franchises.index')->with('success', 'Franchise updated successfully');
    }

    public function getDistricts($stateId)
{
    $districts = District::where('state_id', $stateId)
        ->orderBy('district_name')
        ->get(['id', 'district_name']);

    return response()->json($districts);
}

   public function destroy(StoreMaster $franchise)
{
    $franchise->update([
        'c_store_status' => 'D',
    ]);

    $franchise->delete();

    return redirect()
        ->route('admin.franchises.index')
        ->with('success', 'Franchise deleted successfully.');
}
}