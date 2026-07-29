<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSaleUpload extends Model
{
    use HasFactory;

    protected $table = 'admin_sale_uploads';
    protected $primaryKey = 'n_slno';

    protected $fillable = [
        'd_date',
        'n_store_id',
        'n_product_id',
        'c_bill_no',
        'd_bill_date',
        'n_sold_price',
        'n_buying_rate',
        'n_quantity',
        'c_approve',
        'c_status',
        'batch_id',
    ];

    public function product()
    {
        return $this->belongsTo(ProductMaster::class, 'n_product_id', 'n_product_id');
    }

    public function getTotalSalesAmountAttribute()
    {
        return $this->n_sold_price * $this->n_quantity;
    }

    public function getTotalMarginAmountAttribute()
    {
        return 0.20 * ($this->n_sold_price - $this->n_buying_rate) * $this->n_quantity;
    }

    public function getTotalPurchaseAmountAttribute()
    {
        if (!$this->product) {
            return 0;
        }
        return $this->product->n_purchase_price * $this->n_quantity;
    }
}