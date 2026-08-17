<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Panchayath;
use App\Models\State;
use App\Models\StoreMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function search(Request $request)
    {
        session(['store_search' => $request->search,
            'store_state_id' => $request->state_id,
            'store_district_id' => $request->district_id,
            'store_panchayath_id' => $request->panchayath_id,
        ]);

        return redirect()->route('admin.franchises.index');
    }

    public function clearSearch()
    {
        session()->forget([
            'store_search',
            'store_state_id',
            'store_district_id',
            'store_panchayath_id',
        ]);

        return redirect()->route('admin.franchises.index');
    }

    public function index(Request $request)
    {
        $search = session('store_search');
        $stateId = session('store_state_id');
        $districtId = session('store_district_id');
        $panchayathId = session('store_panchayath_id');

        $stores = StoreMaster::with(['state', 'district', 'panchayath']);

        // Search
        if (! empty($search)) {
            $stores->where(function ($query) use ($search) {
                $query->where('c_store_code', 'LIKE', "%{$search}%")
                    ->orWhere('c_store_name', 'LIKE', "%{$search}%");
            });
        }

        // State
        if (! empty($stateId)) {
            $stores->where('n_state_id', $stateId);
        }

        // Panchayath
        if (! empty($panchayathId)) {
            $stores->where('n_panchayath_id', $panchayathId);
        }

        // District
        if (! empty($districtId)) {
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

        if (! empty($stateId)) {
            $districts = District::where('state_id', $stateId)
                ->orderBy('district_name')
                ->get();
        }

        // Panchayaths
        $panchayaths = collect();

        if (! empty($districtId)) {
            $panchayaths = Panchayath::where('district_id', $districtId)
                ->orderBy('panchayath_name')
                ->get();
        }

        return view('admin.stores.index', compact(
            'stores',
            'states',
            'districts',
            'panchayaths'
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
        // dd('before validation');
        $validated = $request->validate([
            'c_store_code' => 'required|string|max:30|regex:/^[A-Za-z0-9\s\-]+$/|unique:store_masters,c_store_code',

            'c_store_name' => 'required|string|max:100|regex:/^[A-Za-z0-9\s\-]+$/',

            'c_owner_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',

            'c_store_address' => 'required|string|max:255',

            'n_state_id' => 'required|integer',

            'n_district_id' => 'required|integer',

            // NEW
            'c_panchayath' => 'required|string|max:150',

            'c_store_email' => 'required|email:rfc,dns|max:100',

            'n_store_phone' => 'required|regex:/^[6-9]\d{9}$/',

            'c_store_status' => 'required|in:Y,N',

        ], [
            'c_store_code.required' => 'Store code is required',
            'c_store_code.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',

            'c_store_name.required' => 'Store name is required',
            'c_store_name.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',

            'c_owner_name.required' => 'Owner name is required',
            'c_owner_name.regex' => 'Owner name can contain only letters, spaces and hyphens',

            'c_store_address.required' => 'Address is required',

            'n_state_id.required' => 'Please select a state',

            'n_district_id.required' => 'Please select a district',

            // NEW
            'c_panchayath.required' => 'Please enter Panchayath',

            'c_store_email.email' => 'Enter a valid Email id',

            'n_store_phone.regex' => 'Enter a valid Phone number',
        ]);
        // dd('after validation');
        DB::transaction(function () use ($validated) {

            $panchayathName = trim($validated['c_panchayath']);

            // Find existing Panchayath
            $panchayath = Panchayath::where('state_id', $validated['n_state_id'])
                ->where('district_id', $validated['n_district_id'])
                ->whereRaw(
                    'LOWER(TRIM(panchayath_name)) = ?',
                    [strtolower($panchayathName)]
                )
                ->first();

            // If Panchayath does not exist, create it
            if (! $panchayath) {

                $panchayath = Panchayath::create([
                    'state_id' => $validated['n_state_id'],
                    'district_id' => $validated['n_district_id'],
                    'panchayath_name' => $panchayathName,
                    'status' => 'Y',
                ]);
            }
            // dd([
            //     'panchayath' => $panchayath,
            //     'panchayath_id' => $panchayath->id,
            // ]);

            // Create Store using Panchayath ID
            StoreMaster::create([
                'c_store_code' => $validated['c_store_code'],
                'c_store_name' => $validated['c_store_name'],
                'c_owner_name' => $validated['c_owner_name'],
                'c_store_address' => $validated['c_store_address'],

                'n_state_id' => $validated['n_state_id'],
                'n_district_id' => $validated['n_district_id'],

                // Existing ID OR newly created ID
                'n_panchayath_id' => $panchayath->id,

                'c_store_email' => $validated['c_store_email'],
                'n_store_phone' => $validated['n_store_phone'],
                'c_store_status' => $validated['c_store_status'],
            ]);
        });

        return redirect()
            ->route('admin.franchises.index')
            ->with('success', 'Franchise created successfully');
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
        $panchayath = null;

        if ($franchise->n_panchayath_id) {
            $panchayath = Panchayath::find($franchise->n_panchayath_id);
        }

        return view('admin.stores.edit', compact(
            'franchise',
            'states',
            'districts',
            'panchayath'
        ));
    }

    public function update(Request $request, StoreMaster $franchise)
    {
        $validated = $request->validate([
            'c_store_code' => 'required|string|max:30|regex:/^[A-Za-z0-9\s\-]+$/|unique:store_masters,c_store_code,'.$franchise->n_store_id.',n_store_id',

            'c_store_name' => 'required|string|max:100|regex:/^[A-Za-z0-9\s\-]+$/',

            'c_owner_name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',

            'c_store_address' => 'nullable|string|max:255',

            'n_state_id' => 'required|integer',

            'n_district_id' => 'required|integer',

            // Panchayath name from input
            'c_panchayath' => 'required|string|max:150',

            'c_store_email' => 'nullable|email:rfc,dns|max:100',

            'n_store_phone' => 'nullable|regex:/^[6-9]\d{9}$/',

            'c_store_status' => 'required|in:Y,N',

        ], [

            'c_store_code.required' => 'Store code is required',
            'c_store_code.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',

            'c_store_name.required' => 'Store name is required',
            'c_store_name.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',

            'c_owner_name.required' => 'Owner name is required',
            'c_owner_name.regex' => 'Owner name can contain only letters, spaces and hyphens',

            'c_store_address.required' => 'Address is required',

            'n_state_id.required' => 'Please select a state',

            'n_district_id.required' => 'Please select a district',

            'c_panchayath.required' => 'Please enter Panchayath',

            'c_store_email.email' => 'Enter a valid Email id',

            'n_store_phone.regex' => 'Enter a valid Phone number',
        ]);

        DB::transaction(function () use ($validated, $franchise) {

            /*
            |--------------------------------------------------------------------------
            | Find existing Panchayath
            |--------------------------------------------------------------------------
            |
            | Same Panchayath name + same State + same District
            |
            */

            $panchayathName = trim($validated['c_panchayath']);
            // dd($panchayathName);
            $panchayath = Panchayath::where(
                'state_id',
                $validated['n_state_id']
            )
                ->where(
                    'district_id',
                    $validated['n_district_id']
                )
                ->whereRaw(
                    'LOWER(TRIM(panchayath_name)) = ?',
                    [strtolower($panchayathName)]
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Create Panchayath if it doesn't exist
            |--------------------------------------------------------------------------
            */

            if (! $panchayath) {

                $panchayath = Panchayath::create([
                    'state_id' => $validated['n_state_id'],
                    'district_id' => $validated['n_district_id'],
                    'panchayath_name' => $panchayathName,
                    'status' => 'Y',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Update Store
            |--------------------------------------------------------------------------
            */

            $franchise->update([
                'c_store_code' => $validated['c_store_code'],
                'c_store_name' => $validated['c_store_name'],
                'c_owner_name' => $validated['c_owner_name'],
                'c_store_address' => $validated['c_store_address'],

                'n_state_id' => $validated['n_state_id'],
                'n_district_id' => $validated['n_district_id'],

                // Existing or newly-created Panchayath ID
                'n_panchayath_id' => $panchayath->id,

                'c_store_email' => $validated['c_store_email'],
                'n_store_phone' => $validated['n_store_phone'],
                'c_store_status' => $validated['c_store_status'],
            ]);
        });

        return redirect()
            ->route('admin.franchises.index')
            ->with('success', 'Franchise updated successfully');
    }

    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)
            ->orderBy('district_name')
            ->get(['id', 'district_name']);

        return response()->json($districts);
    }

    public function filterPanchayath(Request $request)
    {

        // dd('FILTER PANCHAYATH METHOD HIT', $request->all());
        $panchayaths = Panchayath::where(
            'district_id',
            $request->district
        )
            ->where('status', 'Y')
            ->orderBy('panchayath_name')
            ->get([
                'id',
                'panchayath_name',
            ]);

        return response()->json([
            'panchayaths' => $panchayaths,
        ]);
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
