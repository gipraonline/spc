<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

class AdminUserController extends Controller
{
   public function index()
{
    $users = Admin::with('roles')->latest()
            ->latest()
            ->paginate(10);

    return view('admin.users.index', compact('users'));
}

    public function create()
{
    
    $roles = Role::all();

    return view('admin.users.create', compact('roles'));
}

 public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|email|unique:admins,c_username',
        'role' => 'required|exists:roles,name',
    ]);
// dd($request->all());
   // Get first name
$firstName = explode(' ', trim($request->name))[0];

// Keep only letters
$firstName = preg_replace('/[^A-Za-z]/', '', $firstName);

// If first name is longer than 6 characters, keep only first 6
if (strlen($firstName) > 6) {
    $firstName = substr($firstName, 0, 6);
}

// Capitalize first letter
$firstName = ucfirst(strtolower($firstName));

$upper = chr(rand(65, 90));      // A-Z
$lower = chr(rand(97, 122));     // a-z
$number = rand(100, 999);        // 3 digits

$specialChars = ['@', '#', '$', '%', '&', '!'];
$special = $specialChars[array_rand($specialChars)];

$password = $firstName . $upper . $lower . $special . $number;

    $user = Admin::create([
        'c_name' => $request->name,
        'c_username' => $request->username,
        'c_password' => Hash::make($password),
        'c_status' => 'Active',
    ]);

    // $user->assignRole($request->role);
    $user->syncRoles([$request->role]);
// dd($user);
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

    return view('admin.users.edit', compact('user','roles'));
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

    $user->syncRoles($request->role);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User updated successfully.');
}
public function destroy(Admin $user)
{
    // Prevent deleting Super Admin if desired
    if ($user->hasRole('Super Admin')) {
        return back()->with('error', 'Super Admin cannot be deleted.');
    }

    $user->delete();

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User deleted successfully.');
}
}