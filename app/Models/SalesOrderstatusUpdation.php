<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  SalesOrderstatusUpdation extends Model
{
     protected $table = 'sales_orderstatus_updations';
     public $incrementing = true;

    protected $keyType = 'int';
    protected $primaryKey = 'n_statusupdate_id';

    protected $fillable = [
        'n_sale_id',
        'd_followup_date',
        'c_order_status',
        'remarks',
        'n_created_by',
    ];

    protected $casts = [
        'd_followup_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(
            SalesOrder::class,
            'n_sale_id',
            'n_sl_no'
        );
    }
}
