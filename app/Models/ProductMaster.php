<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaster extends Model
{
    use HasFactory;

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

    public function incentives()
    {
        return $this->hasOne(ProductIncentive::class, 'n_product_id', 'n_product_id');
    }

}