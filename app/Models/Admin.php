<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $guard_name = 'web';

    protected $table = 'admins';

    protected $primaryKey = 'n_role_id';

    public $incrementing = true;

    protected $fillable = [
    'c_name',
    'c_username',
    'c_password',
    'c_status',
];

    protected $hidden = [
        'c_password',
    ];

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
}