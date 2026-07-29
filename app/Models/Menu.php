<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'name',
        'route_name',
        'icon',
        'parent_id',
        'sort_order',
        'status'
    ];
   public function roles()
    {
    return $this->belongsToMany(
        Role::class,
        'role_menu',
        'menu_id',
        'role_id'
    );
    }   
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
    return $this->hasMany(Menu::class, 'parent_id')
        ->orderBy('sort_order')
        ->with('children');
    }
}