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

public function search(Request $request)
    {
        session([
            'employee_search' => $request->employee_search,
            'designation_filter' => $request->n_designation_id,
        ]);

        return redirect()->route('admin.employees.index');
    }

public function clearSearch()
    {
        session()->forget([
            'employee_search',
            'designation_filter',
        ]);

        return redirect()->route('admin.employees.index');
    }

   public function index(Request $request)
{
    $query = EmployeeMaster::with(['designation'])->whereNull('deleted_at');

    // Get filters from session
    $search = session('employee_search');
    $designation = session('designation_filter');

    // Search by employee code or employee name
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('c_employee_code', 'LIKE', "%{$search}%")
              ->orWhere('c_employee_name', 'LIKE', "%{$search}%");
        });
    }

    // Filter by designation
    if (!empty($designation)) {
        $query->where('n_designation_id', $designation);
    }

    $employees = $query->paginate(10);

    // Dropdown data
    $designations = DesignationMaster::where('c_status', 'Y')->get();

    // Employee list for autocomplete
     $employeesForSearch = EmployeeMaster::select( 'n_employee_id', 'c_employee_name', 'c_employee_code' )
                                            ->where('c_status', 'Y')
                                            ->orderBy('c_employee_name')
                                            ->get();

    return view('admin.employees.index', compact('employees', 'designations','employeesForSearch'));
}
    public function create()
    {
        $designations = DesignationMaster::where('c_status', 'Y')->get();

        return view('admin.employees.create', compact('designations'));
    }

    public function store(Request $request)
    {
    //    dd("hits");
       $validated = $request->validate([
            'c_employee_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9_-]+$/',
                'unique:employee_masters,c_employee_code',
            ],

            'c_employee_name'    => 'required|string|max:255',
            'c_employee_address' => 'nullable|string|max:500',

            'c_employee_email' => 'nullable|email|max:255|unique:employee_masters,c_employee_email|unique:employee_masters,c_username',

            'n_employee_phone' => 'nullable|regex:/^[6-9]\d{9}$/',

            'n_designation_id' => 'required|exists:designation_masters,n_designation_id',

            'c_status' => 'required|in:Y,N',

            'account_number' => 'required|digits_between:8,18',

            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

            'bank_name' => 'required|string|max:255',

            'branch_name' => 'required|string|max:255',

], [
    'c_employee_code.required' => 'Employee Code is required.',
    'c_employee_code.unique'   => 'Employee Code already exists.',
    'c_employee_code.regex'    => 'Employee Code can contain only letters, numbers, hyphens (-), and underscores (_).',

    'c_employee_name.required' => 'Employee Name is required.',

    'c_employee_email.email'   => 'Please enter a valid email address.',
    'c_employee_email.unique'  => 'This Email/Username already exists.',

    'n_employee_phone.regex'   => 'Please enter a valid 10-digit mobile number.',

    'n_designation_id.required' => 'Please select a designation.',

    'c_status.required' => 'Please select employee status.',

    'account_number.required' => 'Account Number is required.',
    'account_number.digits_between' => 'Account Number must be between 8 and 18 digits.',

    'ifsc_code.required' => 'IFSC Code is required.',
    'ifsc_code.regex' => 'Please enter a valid IFSC Code.',

    'bank_name.required' => 'Bank Name is required.',
    'branch_name.required' => 'Branch Name is required.',
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
            'c_employee_name'    => 'required|string|max:255',
            'c_employee_address' => 'nullable|string|max:500',

            'c_employee_email' => 'nullable|email|max:255|unique:employee_masters,c_employee_email,' . $employee->n_employee_id . ',n_employee_id',

            'n_employee_phone' => 'nullable|regex:/^[6-9]\d{9}$/',

            'n_designation_id' => 'required|exists:designation_masters,n_designation_id',

            'c_status' => 'required|in:Y,N',

            'account_number' => 'required|digits_between:8,18',

            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

            'bank_name' => 'required|string|max:255',

            'branch_name' => 'required|string|max:255',

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols(),
            ],
        ], [
            'c_employee_email.unique' => 'This email already exists.',
            'n_employee_phone.regex' => 'Please enter a valid 10-digit mobile number.',
            'ifsc_code.regex' => 'Please enter a valid IFSC code.',
            'account_number.digits_between' => 'Account number must be between 8 and 18 digits.',
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

    public function destroy($id)
    {
        $employee = EmployeeMaster::findOrFail($id);

        // Update employee status to 'D' (Deleted)

        $employee->update([
            'c_status' => 'D',
        ]);

        // Soft delete the employee by setting the deleted_at timestamp

        $employee->delete();

        return redirect()->route('admin.employees.index')
                        ->with('success', 'Employee deleted successfully.');
    }

}
