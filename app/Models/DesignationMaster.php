<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationMaster extends Model
{
    use HasFactory;

    protected $table = 'designation_masters';

    protected $primaryKey = 'n_designation_id';

    protected $fillable = [
        'c_designation',
        'identifier',
        'hierarchy_level',
        'c_status',
    ];
}