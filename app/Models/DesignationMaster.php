<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationMaster extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'designations';

    protected string $auditEntity = 'Designation';

    protected array $auditSubjectColumns = ['c_designation'];

    use HasFactory;

    protected $table = 'designation_masters';

    protected $primaryKey = 'n_designation_id';

    protected $fillable = [
        'c_designation',
        'identifier',
        'hierarchy_level',
        'parent_designation_id',
        'c_status',
    ];

    public function parent()
    {
        return $this->belongsTo(
            DesignationMaster::class,
            'parent_designation_id',
            'n_designation_id'
        );
    }

    public function children()
    {
        return $this->hasMany(
            DesignationMaster::class,
            'parent_designation_id',
            'n_designation_id'
        );
    }

    /**
     * All designation ids in this designation's own branch below it
     * (children, grandchildren, ...). Used to restrict "employees below
     * me" / dropdowns to the actual org-chart branch instead of every
     * designation at a lower hierarchy_level.
     */
    public function descendantIds(bool $includeSelf = false): array
    {
        $ids = $includeSelf ? [$this->n_designation_id] : [];

        $frontier = [$this->n_designation_id];

        while (! empty($frontier)) {
            $childIds = static::whereIn('parent_designation_id', $frontier)
                ->pluck('n_designation_id')
                ->all();

            if (empty($childIds)) {
                break;
            }

            $ids = array_merge($ids, $childIds);
            $frontier = $childIds;
        }

        return $ids;
    }
}