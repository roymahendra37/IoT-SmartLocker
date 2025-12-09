<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_id',
        'type',
        'name',
        'email',
        'receiver_name',
        'receiver_email',
        'duration',
        'qr_code',
        'qr_image',
        'qr_usage_count',
        'status',
        'start_time',
        'end_time',
        'fcm_token',
        'notified',
        'esp_notified'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }
}