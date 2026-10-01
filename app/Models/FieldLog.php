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
        'check_in_latitude',
        'check_in_longitude',
        'check_in_accuracy_m',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_accuracy_m',
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

    public function hasCheckInLocation(): bool
    {
        return \App\Support\Geo::valid($this->check_in_latitude, $this->check_in_longitude);
    }

    public function hasCheckOutLocation(): bool
    {
        return \App\Support\Geo::valid($this->check_out_latitude, $this->check_out_longitude);
    }

    public function checkInMapUrl(): ?string
    {
        return \App\Support\Geo::mapsUrl($this->check_in_latitude, $this->check_in_longitude);
    }

    public function checkOutMapUrl(): ?string
    {
        return \App\Support\Geo::mapsUrl($this->check_out_latitude, $this->check_out_longitude);
    }

    /** Straight-line km between check-in and check-out (null when either is missing). */
    public function distanceMovedKm(): ?float
    {
        if (! $this->hasCheckInLocation() || ! $this->hasCheckOutLocation()) {
            return null;
        }

        return round(\App\Support\Geo::distanceKm(
            (float) $this->check_in_latitude, (float) $this->check_in_longitude,
            (float) $this->check_out_latitude, (float) $this->check_out_longitude
        ), 2);
    }

    public function tasks()
    {
        return $this->hasMany(FieldLogTask::class);
    }
}