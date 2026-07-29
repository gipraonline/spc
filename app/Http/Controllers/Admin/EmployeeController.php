<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignationMaster;
use App\Models\EmployeeMaster;
use App\Models\EmployeeEditLog;
use App\Models\KycSubmission;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class EmployeeController extends Controller
{

   public function index(Request $request)
{
    $query = EmployeeMaster::query()
        ->select([
            'employee_masters.*',
        ])
        ->leftJoin(
            'designation_masters as d',
            'employee_masters.n_designation_id',
            '=',
            'd.n_designation_id'
        );

    // Employee Search (Name or Code)
    if ($request->filled('employee_search')) {
        $search = trim($request->employee_search);

        $query->where(function ($q) use ($search) {
            $q->where('employee_masters.c_employee_name', 'LIKE', "%{$search}%")
              ->orWhere('employee_masters.c_employee_code', 'LIKE', "%{$search}%");
        });
    }

    // Designation Filter
    if ($request->filled('n_designation_id')) {
        $query->where(
            'employee_masters.n_designation_id',
            $request->n_designation_id
        );
    }

    // Order By
    $query->orderBy('employee_masters.c_employee_name', 'ASC');

    // Pagination
    $employees = $query->paginate(10)->appends($request->all());

    // Employee list for autocomplete
    $employeesForSearch = EmployeeMaster::select(
        'n_employee_id',
        'c_employee_name',
        'c_employee_code'
    )
    ->where('c_status', 'Y')
    ->orderBy('c_employee_name')
    ->get();

    // Designation list
    $designations = DesignationMaster::where('c_status', 'Y')
        ->orderBy('c_designation')
        ->get();

    return view('admin.employees.index', compact(
        'employees',
        'designations',
        'employeesForSearch'
    ));
}

    public function create()
    {
        $designations = DesignationMaster::where('c_status', 'Y')->get();
       
        return view('admin.employees.create', compact('designations'));
    }

    public function store(Request $request)
    {
       
           $validated = $request->validate([
        'c_employee_code'    => 'required|string|unique:employee_masters,c_employee_code',
        'c_employee_name'    => 'required|string',
        'c_employee_address' => 'nullable|string',
        'c_employee_email'   => 'nullable|email|unique:employee_masters,c_employee_email|unique:employee_masters,c_username',
        'n_employee_phone'   => 'nullable|string',
        'n_designation_id'   => 'nullable|exists:designation_masters,n_designation_id',

        'account_number'     => 'required|string|max:30',
        'ifsc_code'          => 'required|string|max:15',
        'bank_name'          => 'nullable|string|max:255',
        'branch_name'        => 'nullable|string|max:255',
    ], [
        'c_employee_email.unique' => 'This Email/Username already exists.',
    ]);

    DB::beginTransaction();

    try {

        // Employee
        $employee = EmployeeMaster::create([
            'c_employee_code'    => $validated['c_employee_code'],
            'c_username'         => $validated['c_employee_code'],
            'c_password'         => Hash::make('Password@123'),
            'c_employee_name'    => $validated['c_employee_name'],
            'c_employee_address' => $validated['c_employee_address'] ?? null,
            'c_employee_email'   => $validated['c_employee_email'] ?? null,
            'n_employee_phone'   => $validated['n_employee_phone'] ?? null,
            'n_designation_id'   => $validated['n_designation_id'] ?? null,
            'c_status'           => $request->c_status,
        ]);

        // Bank Details
        KycSubmission::create([
            'n_employee_id'  => $employee->n_employee_id,
            'bank_name'      => $request->bank_name,
            'bank_branch'    => $request->branch_name,
            'account_number' => $request->account_number,
            'ifsc_code'      => $request->ifsc_code,
            'document_path'  => '',
            'status'         => 'Active',
        ]);

        DB::commit();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function show(EmployeeMaster $employee)
    {
        $employee = EmployeeMaster::with('designation')->get();

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(EmployeeMaster $employee)
    {

        $designations = DesignationMaster::where('c_status', 'Y')->get();
    
        $kyc = KycSubmission::where('n_employee_id', $employee->n_employee_id)
                        ->where('status', 'Active')
                        ->first();
                    

        return view('admin.employees.edit', compact('employee', 'designations','kyc'));
    }

    public function update(Request $request, EmployeeMaster $employee)
    {
    $validated = $request->validate([
        'c_employee_name'    => 'required|string',
        'c_employee_address' => 'nullable|string',
        'c_employee_email'   => 'nullable|email',
        'n_employee_phone'   => 'nullable|string',
        'n_designation_id'   => 'nullable|exists:designation_masters,n_designation_id',
        'c_status'           => 'required|in:Y,N',

        'account_number' => 'required|string|max:30',
        'ifsc_code'      => 'required|string|max:15',
        'bank_name'      => 'nullable|string|max:255',
        'branch_name'    => 'nullable|string|max:255',

        'password' => [
            'nullable',
            'confirmed',
            Password::min(8)->letters()->numbers()->symbols(),
        ],
    ]);

    DB::beginTransaction();

    try {

        EmployeeEditLog::create([
            'n_employee_id'        => $employee->n_employee_id,
            'n_pre_designation_id' => $employee->n_designation_id,
            'n_new_designation_id' => $request->n_designation_id,
        ]);

        // Update employee
        $employee->update([
            'c_employee_name'    => $request->c_employee_name,
            'c_employee_address' => $request->c_employee_address,
            'c_employee_email'   => $request->c_employee_email,
            'n_employee_phone'   => $request->n_employee_phone,
            'n_designation_id'   => $request->n_designation_id,
            'c_status'           => $request->c_status,
        ]);

        // Update password only if entered
        if ($request->filled('password')) {
            $employee->update([
                'c_password' => Hash::make($request->password),
            ]);
        }

        // Update or create bank details
        $employee->kycSubmission()->updateOrCreate(
            ['n_employee_id' => $employee->n_employee_id],
            [
                'bank_name'      => $request->bank_name,
                'bank_branch'    => $request->branch_name,
                'account_number' => $request->account_number,
                'ifsc_code'      => $request->ifsc_code,
                'document_path'  => '',
                'status'         => 'Active',
            ]
        );

        DB::commit();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function destroy(EmployeeMaster $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully');
    }  
  
}