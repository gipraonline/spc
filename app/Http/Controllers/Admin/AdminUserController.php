<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\EmployeeMaster;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = Admin::with('roles')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        // Employees who don't already have an admin account
        $employees = EmployeeMaster::where('c_status', 'Y')
            ->whereNotIn('c_employee_email', function ($query) {
                $query->select('c_username')->from('admins');
            })
            ->orderBy('c_employee_name')
            ->get();
// dd(EmployeeMaster::all());
        return view('admin.users.create', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee_masters,n_employee_id|unique:admins,n_employee_id',
            'role' => 'required|exists:roles,name',
        ]);

        // Get selected employee
        $employee = EmployeeMaster::findOrFail($request->employee_id);

        $name = $employee->c_employee_name;
        $username = $employee->c_employee_email;

        // Safety check
        if (Admin::where('c_username', $username)->exists()) {
            return back()
                ->withErrors(['employee_id' => 'This employee already has a user account.'])
                ->withInput();
        }

        // Generate Password
        $firstName = explode(' ', trim($name))[0];
        $firstName = preg_replace('/[^A-Za-z]/', '', $firstName);

        if (strlen($firstName) > 6) {
            $firstName = substr($firstName, 0, 6);
        }

        $firstName = ucfirst(strtolower($firstName));

        $upper = chr(rand(65, 90));
        $lower = chr(rand(97, 122));
        $number = rand(100, 999);

        $specialChars = ['@', '#', '$', '%', '&', '!'];
        $special = $specialChars[array_rand($specialChars)];

        $password = $firstName . $upper . $lower . $special . $number;

        // Create Admin User
        $user = Admin::create([
            'n_employee_id' => $employee->n_employee_id,
            'c_name' => $name,
            'c_username' => $username,
            'c_password' => Hash::make($password),
            'c_status' => 'Active',
        ]);

        // Assign Role
        $user->syncRoles([$request->role]);

        return redirect()
            ->route('admin.users.index')
            ->with([
                'success' => 'User created successfully.',
                'password' => $password,
            ]);
    }

    public function edit(Admin $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, Admin $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|email|unique:admins,c_username,' . $user->n_role_id . ',n_role_id',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'c_name' => $request->name,
            'c_username' => $request->username,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Admin $user)
    {
        if ($user->hasRole('Super Admin')) {
            return back()->with('error', 'Super Admin cannot be deleted.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}