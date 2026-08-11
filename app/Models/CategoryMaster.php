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
        'n_parent_category_id',
        'c_status',
    ];


    public function parent()
{
    return $this->belongsTo(
        self::class,
        'n_parent_category_id',
        'n_category_id'
    );
}

public function children()
{
    return $this->hasMany(
        self::class,
        'n_parent_category_id',
        'n_category_id'
    );
}
}