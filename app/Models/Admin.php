<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;

    public const STATUS_ACTIVE = 'Active';

    protected $guard_name = 'web';

    protected $table = 'admins';

    protected $primaryKey = 'n_role_id';

    public $incrementing = true;

    protected $fillable = [
        'n_employee_id',
        'c_name',
        'c_username',
        'c_password',
        'c_status',

        // Temporary initial password
        'initial_password',
        'initial_password_expires_at',
    ];

    protected $hidden = [
        'c_password',
        'initial_password',
    ];

    protected $casts = [
        // Fail-safe encrypted cast: legacy rows written under a previous
        // APP_KEY read as null instead of throwing DecryptException.
        'initial_password' => \App\Casts\EncryptedNullable::class,
        'initial_password_expires_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return $this->c_status === self::STATUS_ACTIVE;
    }

    public function getAuthPassword()
    {
        return $this->c_password;
    }

    // Display Name
    public function getNameAttribute()
    {
        return $this->c_name;
    }

    public function getUsernameAttribute()
    {
        return $this->c_username;
    }

    public function getEmailAttribute()
    {
        return $this->c_username;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'n_role_id', 'id');
    }

    public function fieldLogs()
    {
        return $this->hasMany(FieldLog::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(
            EmployeeMaster::class,
            'n_employee_id',
            'n_employee_id'
        );
    }
}
