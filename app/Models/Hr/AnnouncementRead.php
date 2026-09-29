<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AnnouncementRead extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];
    public $timestamps = false;

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
