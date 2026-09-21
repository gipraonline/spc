<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
