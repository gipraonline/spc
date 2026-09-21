<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_products';

    protected $primaryKey = 'n_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [

        'n_order_id',
        'n_category_id',
        'n_sub_category_id',
        'product_id',
        'c_hsn_code',
        'product_price',
        'qty',
        'c_unit',
        'discount',
        'n_gst_percentage',
        'gst_amount',
        'discounted_price',
        'product_total',
    ];

    /**
     * Product Relationship
     */
    public function product()
    {
        return $this->belongsTo(
            ProductMaster::class,
            'product_id',
            'n_product_id'
        );
    }
     public function category()
    {
        return $this->belongsTo(
            CategoryMaster::class,
            'n_category_id',
            'n_category_id'
        );
    }
     public function subCategory()
    {
        return $this->belongsTo(
            CategoryMaster::class,
            'n_category_id',
            'n_parent_category_id'
        );
    }
}
