<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreMaster;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function search(Request $request)
    {
        session(['store_search' => $request->search]);

        return redirect()->route('admin.franchises.index');
    }

    public function clearSearch()
    {
        session()->forget('store_search');

        return redirect()->route('admin.franchises.index');
    }

        public function index(Request $request)
    {

        $stores = StoreMaster::query();
        $search = session('store_search');

        if (!empty($search)) {

            $stores ->where(function ($query) use ($search) {
            $query  ->where('c_store_code', 'LIKE', "%{$search}%")
                    ->orWhere('c_store_name', 'LIKE', "%{$search}%");
        });
    }

    $stores = $stores->paginate(15);

        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'c_store_code' => 'required|string|max:30|regex:/^[A-Za-z0-9\s\-]+$/|unique:store_masters,c_store_code',
            'c_store_name' => 'required|string|max:100|regex:/^[A-Za-z0-9\s\-]+$/',
            'c_store_address' => 'required|string|max:255',
            'c_store_email' => 'required|email:rfc,dns|max:100',
            'n_store_phone' => 'required|regex:/^[6-9]\d{9}$/',
            'c_store_status' => 'required|in:Y,N',
        ], ['c_store_code.required' => 'Store code is required',
            'c_store_code.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',
            'c_store_name.required' => 'Store name is required',
            'c_store_name.regex' => 'Special characters are not allowed.Use letters, numbers, spaces',
            'c_store_address.required' => 'Address is required',
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
        return view('admin.stores.edit', compact('franchise'));
    }

    public function update(Request $request, StoreMaster $franchise)
    {
        $validated = $request->validate([
            'c_store_code' => 'required|string|max:30|regex:/^[A-Za-z0-9\s\-]+$/|unique:store_masters,c_store_code,'.$franchise->n_store_id.',n_store_id',
            'c_store_name' => 'required|string|max:100|regex:/^[A-Za-z0-9\s\-]+$/',
            'c_store_address' => 'nullable|string|max:255',
            'c_store_email' => 'nullable|email:rfc,dns|max:100',
            'n_store_phone' => 'nullable|regex:/^[6-9]\d{9}$/',
            'c_store_status' => 'required|in:Y,N',
        ], ['c_store_code.required' => 'Store code is required',
            'c_store_code.regex' => 'Special characters are not allowed. Use letters, numbers, spaces',
            'c_store_name.required' => 'Store name is required',
            'c_store_name.regex' => 'Special characters are not allowed.Use letters, numbers, spaces',
            'c_store_address.required' => 'Address is required',
            'c_store_email.email' => 'Enter a valid Email id',
            'n_store_phone.regex' => 'Enter a valid Phone number',
            ]);

        $franchise->update($validated);

        return redirect()->route('admin.franchises.index')->with('success', 'Franchise updated successfully');
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