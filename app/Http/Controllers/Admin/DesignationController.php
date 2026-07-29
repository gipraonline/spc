<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignationMaster;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = DesignationMaster::all();

        return view('admin.designations.index', compact('designations'));
    }

    public function create()
    {
        return view('admin.designations.create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
           'c_designation' => [
        'required',
        'string',
        'max:30',
        'unique:designation_masters,c_designation',

        // Only letters and &
        'regex:/^[A-Za-z]+(&[A-Za-z]+)*$/',
    ],

        'c_status' => 'required|in:Y,N',
        'c_status.required' => 'Please select a Status.',

    ], [
        'c_designation.required' => 'Designation is required.',
        'c_designation.max' => 'Designation must not exceed 30 characters.',
        'c_designation.unique' => 'This designation already exists.',
        'c_designation.regex' => 'Only letters and & are allowed (no spaces).',
    ]);

        DesignationMaster::create($validated);

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully');
    }

    public function show(DesignationMaster $designation)
    {
        return view('admin.designations.show', compact('designation'));
    }

    public function edit(DesignationMaster $designation)
    {
        return view('admin.designations.edit', compact('designation'));
    }

    public function update(Request $request, DesignationMaster $designation)
    {
        $validated = $request->validate([
            'c_designation' => 'required|string|unique:designation_masters,c_designation,'.$designation->n_designation_id.',n_designation_id',
            'c_designation_status' => 'required|in:Y,N',
        ]);

        $designation->update($validated);

        return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully');
    }

    public function destroy(DesignationMaster $designation)
    {
        $designation->delete();

        return redirect()->route('admin.designations.index')->with('success', 'Designation deleted successfully');
    }
}
