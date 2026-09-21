<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitMaster extends Model
{
    use HasFactory;

    protected $table = 'unit_masters';

    protected $primaryKey = 'n_unit_id';

    protected $fillable = [
        'c_unit_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
