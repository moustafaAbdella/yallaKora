<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAds extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'app_info' => 'array',
        'ip_info' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function device()
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
    }
}
