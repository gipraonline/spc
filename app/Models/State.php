<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    protected $primaryKey = 'n_state_id';

    protected $fillable = [
        'country_id',
        'name',
        'state_code',
        'status',
    ];

    public function Districts()
    {
        return $this->hasMany(District::class, 'state_id', 'state_id');
    }
}
