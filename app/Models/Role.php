<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'roles';

    protected string $auditEntity = 'Role';

    protected array $auditSubjectColumns = ['name'];

    protected $fillable = [
        'name',
        'identifier',
        'guard_name',
        'hr_access',
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
