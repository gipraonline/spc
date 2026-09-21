<?php

namespace App\Http\Controllers\Hr;


use App\Models\Hr\AuditLog;
use App\Models\Hr\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SystemController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('system');

        $totalUsers = User::count();
        $activeUsersCount = User::where('is_active', true)->count();
        $totalAuditEntries = AuditLog::count();

        $users = User::orderBy('name')->paginate(15, ['*'], 'usersPage')->withQueryString();
        $editingUser = $request->filled('user') ? User::find($request->integer('user')) : null;
        $auditLog = AuditLog::with('user')->orderByDesc('id')->paginate(20, ['*'], 'auditPage')->withQueryString();

        return view('hr.modules.system', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'system',
            'users' => $users,
            'totalUsers' => $totalUsers,
            'activeUsersCount' => $activeUsersCount,
            'totalAuditEntries' => $totalAuditEntries,
            'editingUser' => $editingUser,
            'auditLog' => $auditLog,
        ]));
    }

    public function storeUser(Request $request)
    {
        $this->abortUnlessModuleAllowed('system');
        abort_unless($this->currentRole() === 'super_admin', 403);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'role' => 'required|in:employee,manager,hr_admin,super_admin',
        ]);

        $user = User::create(array_merge($data, [
            'password' => Hash::make('changeme'),
            'is_active' => true,
        ]));

        AuditLog::create([
            'user_id' => $this->currentUser()->id,
            'action' => 'CREATE',
            'module' => 'users',
            'record_id' => $user->id,
            'new_value' => json_encode(['name' => $user->name, 'role' => $user->role]),
            'created_at' => now(),
        ]);

        return redirect()->route('hr.system.index')->with('status', 'User "'.$user->name.'" created.');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->abortUnlessModuleAllowed('system');
        abort_unless($this->currentRole() === 'super_admin', 403);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'role' => 'required|in:employee,manager,hr_admin,super_admin',
            'is_active' => 'nullable|boolean',
        ]);

        $old = $user->only(['role', 'is_active']);
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        AuditLog::create([
            'user_id' => $this->currentUser()->id,
            'action' => 'UPDATE',
            'module' => 'users',
            'record_id' => $user->id,
            'old_value' => json_encode($old),
            'new_value' => json_encode($user->only(['role', 'is_active'])),
            'created_at' => now(),
        ]);

        return redirect()->route('hr.system.index')->with('status', 'User "'.$user->name.'" updated.');
    }
}
