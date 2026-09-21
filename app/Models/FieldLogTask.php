<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldLogTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_log_id',
        'task',
        'status',
        'pending_remark',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function fieldLog()
    {
        return $this->belongsTo(FieldLog::class);
    }
}