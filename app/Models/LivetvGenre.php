<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivetvGenre extends Model

{

    protected $appends = ['name'];
    
    public function genre()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function livetv()
    {
        return $this->belongsTo('App\Models\Livetv', 'livetv_id');
    }



    public function getNameAttribute()
    {
        return $this->genre->name;
    }



}
