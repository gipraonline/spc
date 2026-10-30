<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\DesignationMaster;
use App\Models\EmployeeMaster;
use App\Models\Role;
use App\Services\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /** Role whose users are visible only to a Gipra Admin. */
    private const HIDDEN_ROLES = ['Gipra Admin'];

    /** Roles nobody can assign from this screen (matched by role identifier). */
    private const UNASSIGNABLE_ROLE_IDENTIFIERS = ['CHAIRMAN', 'MD', 'CEO', 'COO_CFO'];

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

    /**
     * Roles offered in the create / edit dropdowns. Chairman, MD, CEO and
     * COO / CFO are never offered. Roles are listed by hierarchy level (taken
     * from the designation with the same identifier). When editing, the user's current role is
     * kept in the list so saving the form doesn't silently change it.
     */
    private function assignableRoles(?Admin $editing = null)
    {
        $currentRoles = $editing ? $editing->getRoleNames()->all() : [];

        // identifier => hierarchy level (lower number = higher in the org)
        $levels = DesignationMaster::pluck('hierarchy_level', 'identifier');

        $roles = Role::query()
            ->where(function ($q) use ($currentRoles) {
                $q->where(function ($w) {
                    $w->whereNull('identifier')
                        ->orWhereNotIn('identifier', self::UNASSIGNABLE_ROLE_IDENTIFIERS);
                });

                if ($currentRoles) {
                    $q->orWhereIn('name', $currentRoles);
                }
            })
            ->when(
                ! $this->isGipraAdmin(),
                fn ($q) => $q->where('name', '!=', 'Gipra Admin')
            )
            ->get();

        // Roles with no matching designation (Super Admin, Gipra Admin) go first.
        return $roles
            ->sortBy(fn ($role) => sprintf(
                '%03d-%s',
                $levels[$role->identifier] ?? 0,
                $role->name
            ))
            ->values();
    }

    /** Server-side check that the submitted role is one we offered. */
    private function ensureRoleAllowed(string $roleName, ?Admin $editing = null): void
    {
        abort_unless(
            $this->assignableRoles($editing)->contains('name', $roleName),
            403,
            'This role cannot be assigned.'
        );
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

        $this->ensureRoleAllowed($request->role);

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

        $roles = $this->assignableRoles($user);

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

        $this->ensureRoleAllowed($request->role, $user);

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