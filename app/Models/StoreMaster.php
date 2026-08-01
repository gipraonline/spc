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
        'c_store_address',
        'c_store_email',
        'n_store_phone',
        'c_store_status',
    ];

    /**
     * Cluster Manager Relationship
     * Update the model and key names if different.
     */
    public function clusterManager()
    {
        return $this->belongsTo(ClusterManager::class, 'n_clustor_manager_id');
    }
}