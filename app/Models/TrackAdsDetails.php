<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAdsDetails extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'app_info' => 'array',
        'ip_info' => 'array',
        'ad_info' => 'array',
    ];

    public function track_ad()
    {
        return $this->belongsTo(TrackAds::class, 'track_ad_id');
    }
}
