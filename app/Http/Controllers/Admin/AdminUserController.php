<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\EmployeeMaster;
use App\Models\Role;
use App\Services\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /** Role whose users are visible only to a Gipra Admin. */
    private const HIDDEN_ROLES = ['Gipra Admin'];

    /** Specific accounts visible only to a Gipra Admin (matched by username). */
    private const HIDDEN_USERNAMES = ['admin@spc.com'];

    private function isGipraAdmin(): bool
    {
        return (bool) auth('web')->user()?->hasRole('Gipra Admin');
    }

    /** Limit an Admin query to the users the signed-in person may see. */
    private function visibleUsers($query)
    {
        if (! $this->isGipraAdmin()) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', self::HIDDEN_ROLES);
            })->whereNotIn('c_username', self::HIDDEN_USERNAMES);
        }

        return $query;
    }

    /** Roles offered in the create / edit dropdowns. */
    private function assignableRoles()
    {
        return Role::when(
            ! $this->isGipraAdmin(),
            fn ($q) => $q->where('name', '!=', 'Gipra Admin')
        )->get();
    }

    /** 404 when a non-Gipra user tries to reach a hidden user by URL. */
    private function guardUser(Admin $user): void
    {
        abort_if(
            ! $this->isGipraAdmin() && (
                $user->hasAnyRole(self::HIDDEN_ROLES)
                || in_array($user->c_username, self::HIDDEN_USERNAMES, true)
            ),
            404
        );
    }

    public function index()
    {
        $users = $this->visibleUsers(Admin::with('roles'))
            ->latest()
            ->paginate(10);

        $hasActivePasswords = $this->visibleUsers(Admin::query())
            ->whereNotNull('initial_password')
            ->whereNotNull('initial_password_expires_at')
            ->where('initial_password_expires_at', '>=', now())
            ->exists();

        return view('admin.users.index', compact('users', 'hasActivePasswords'));
    }

    public function create()
    {
        $roles = $this->assignableRoles();

        // Employees who don't already have an admin account
        $employees = EmployeeMaster::where('c_status', 'Y')
            ->whereNotIn('c_employee_email', function ($query) {
                $query->select('c_username')->from('admins');
            })
            ->orderBy('c_employee_name')
            ->get();

        return view('admin.users.create', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee_masters,n_employee_id|unique:admins,n_employee_id',
            'role' => 'required|exists:roles,name',
        ]);

        // Only a Gipra Admin may assign the Gipra Admin role
        abort_if(
            $request->role === 'Gipra Admin' && ! $this->isGipraAdmin(),
            403
        );

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

        $password = $firstName.$upper.$lower.$special.$number;

        // Create Admin User
        $user = Admin::create([
            'n_employee_id' => $employee->n_employee_id,
            'c_name' => $name,
            'c_username' => $username,

            // Password used for login
            'c_password' => Hash::make($password),

            // Temporary copy for admin viewing
            'initial_password' => $password,

            // Available for 48 hours
            'initial_password_expires_at' => now()->addDays(2),

            'c_status' => 'Active',
        ]);

        // Assign Role
        $user->syncRoles([$request->role]);

        AuditTrail::record('users', 'UPDATE', (int) $user->getKey(), null, [
            'role' => $request->role,
            '_entity' => 'User login',
            '_subject' => $user->c_name,
        ]);

        return redirect()
            ->route('admin.users.index');
    }

    public function showPassword(Admin $user)
    {
        // Only HR Manager and Gipra Admin can view passwords
        if (
            ! auth('web')->check() ||
            ! auth('web')->user()->hasAnyRole(['HR Manager', 'Gipra Admin'])
        ) {
            abort(403);
        }

        $this->guardUser($user);

        // Check whether password viewing has expired
        if (
            ! $user->initial_password_expires_at ||
            now()->greaterThan($user->initial_password_expires_at)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'The initial password has expired and can no longer be viewed.',
            ], 403);
        }

        if (! $user->initial_password) {
            return response()->json([
                'success' => false,
                'message' => 'Initial password is not available.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'password' => $user->initial_password,
            'expires_at' => $user->initial_password_expires_at->format('d M Y h:i A'),
        ]);
    }

    public function copyAllLoginDetails()
    {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['HR Manager', 'Gipra Admin'])) {
            abort(403, 'You are not authorized to view passwords.');
        }

        $users = $this->visibleUsers(Admin::query())
            ->whereNotNull('initial_password')
            ->whereNotNull('initial_password_expires_at')
            ->where('initial_password_expires_at', '>=', now())
            ->orderBy('c_name')
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active passwords are available.',
            ], 404);
        }

        $loginDetails = $users->map(function ($user) {
            return implode("\n", [
                $user->c_name,
                'Username: '.$user->c_username,
                'Password: '.$user->initial_password,
                '',
            ]);
        })->implode("\n");

        return response()->json([
            'success' => true,
            'count' => $users->count(),
            'details' => $loginDetails,
        ]);
    }

    public function edit(Admin $user)
    {
        $this->guardUser($user);

        $roles = $this->assignableRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, Admin $user)
    {
        $this->guardUser($user);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|email|unique:admins,c_username,'.$user->n_role_id.',n_role_id',
            'role' => 'required|exists:roles,name',
        ]);

        // Only a Gipra Admin may assign the Gipra Admin role
        abort_if(
            $request->role === 'Gipra Admin' && ! $this->isGipraAdmin(),
            403
        );

        $oldRole = $user->getRoleNames()->first();

        $user->update([
            'c_name' => $request->name,
            'c_username' => $request->username,
        ]);

        $user->syncRoles([$request->role]);

        if ($oldRole !== $request->role) {
            AuditTrail::record('users', 'UPDATE', (int) $user->getKey(), ['role' => $oldRole], [
                'role' => $request->role,
                '_entity' => 'User login',
                '_subject' => $user->c_name,
            ]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Admin $user)
    {
        $this->guardUser($user);

        if ($user->hasRole('Super Admin')) {
            return back()->with('error', 'Super Admin cannot be deleted.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}