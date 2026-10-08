<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'announcements';

    protected string $auditEntity = 'Announcement';

    protected array $auditSubjectColumns = ['title'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = true;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    public function isReadBy(int $userId): bool
    {
        return $this->relationLoaded('reads')
            ? $this->reads->contains('user_id', $userId)
            : $this->reads()->where('user_id', $userId)->exists();
    }
}
