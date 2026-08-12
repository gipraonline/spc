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
        'n_category_id',
        'c_product_code',
        'c_product_name',
        'n_purchase_price',
        'n_selling_price',
        'n_mrp',
        'c_unit',
        'c_hsn_code',
        'n_gst_percentage',
        'c_status',
    ];

     public function category()
    {
        return $this->belongsTo(CategoryMaster::class,'n_category_id','n_category_id');
    }

}