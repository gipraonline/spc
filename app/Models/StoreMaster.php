<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreMaster extends Model
{
    use SoftDeletes;

    protected $table = 'store_masters';

    protected $primaryKey = 'n_store_id';

    protected $fillable = [
        'c_store_code',
        'n_clustor_manager_id',
        'c_store_name',
        'c_owner_name',
        'c_store_address',
        'n_state_id',
        'n_district_id',
        'n_panchayath_id',
        'latitude',
        'longitude',
        'c_store_email',
        'n_store_phone',
        'c_store_status',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'n_state_id', 'n_state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'n_district_id', 'id');
    }

    public function panchayath()
    {
        return $this->belongsTo(Panchayath::class, 'n_panchayath_id', 'id');
    }
}
