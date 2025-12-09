<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use HasFactory;

    protected $fillable = [
        'locker_number',
        'locker_code',
        'status',
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}