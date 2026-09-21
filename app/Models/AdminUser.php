<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
use HasRoles;

protected $guard_name = 'web';

protected $fillable = [
'name',
'username',
'password',
'status'
];

protected $hidden = [
'password'
];

public function fieldLogs()
{
    return $this->hasMany(FieldLog::class, 'user_id');
}

}