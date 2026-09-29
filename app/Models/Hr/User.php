<?php

namespace App\Models\Hr;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = true;

    protected $hidden = ['password'];

    /**
     * Single sign-on bridge: find the HR account that belongs to a given
     * SPC admin, matched by email (admins.c_username <-> spc_hr.users.email).
     * Returns null if the admin has no linked/active HR account, so admins
     * without HR access are left untouched.
     */
    public static function findForSpcAdmin(?Admin $admin): ?self
    {
        if (! $admin || ! $admin->c_username) {
            return null;
        }

        return static::whereRaw('LOWER(email) = ?', [Str::lower(trim($admin->c_username))])
            ->where('is_active', true)
            ->first();
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'employee' => 'Employee',
            'manager' => 'Reporting Manager',
            'hr_admin' => 'HR Admin',
            'super_admin' => 'Super Admin',
            default => ucfirst($this->role),
        };
    }
}
