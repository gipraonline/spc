<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'check_in_time',
        'check_out_time',
        'check_in_remark',
        'check_out_remark',
        'status',
    ];

    protected $casts = [
        'work_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function admin()
{
    return $this->belongsTo(Admin::class, 'user_id');
}

    public function tasks()
    {
        return $this->hasMany(FieldLogTask::class);
    }
}