<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'identifier',
        'guard_name',
    ];

    public function menus()
    {
        return $this->belongsToMany(
            Menu::class,
            'role_menu',
            'role_id',
            'menu_id'
        );
    }

    public function designation()
    {
        return $this->hasOne(
            DesignationMaster::class,
            'identifier',
            'identifier'
        );
    }
}
