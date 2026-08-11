<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryMaster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'category_masters';

    protected $primaryKey = 'n_category_id';

    protected $fillable = [
        'c_category_code',
        'c_category_name',
        'c_status',
    ];
}