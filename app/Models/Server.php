<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = ['name','useragent','player','domain','embed','type','supported_hosts'];
    protected $casts = [
        'embed' => 'int',
        'supported_hosts' => 'int',
        'hls' => 'int',
    ];

    public function movie()
    {
        return $this->belongsTo('App\Models\Movie');
    }
}
