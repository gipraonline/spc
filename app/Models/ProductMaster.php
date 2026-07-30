<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMaster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_masters';

    protected $primaryKey = 'n_product_id';

    protected $fillable = [
        'c_product_code',
        'c_product_name',
        'n_purchase_price',
        'n_selling_price',
        'n_mrp',
        'c_status',
    ];

}