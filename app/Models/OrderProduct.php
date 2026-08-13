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
        'product_id',
        'product_price',
        'qty',
        'discount',
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
}