<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';
    public $timestamps = true;
    protected $fillable = [
        'district_name',
        'state_id',
    ];

    /**
     * Relationship: District belongs to a State
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
