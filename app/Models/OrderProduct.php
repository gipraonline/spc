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
        'product_total',
    ];

    /**
     * Product Relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
