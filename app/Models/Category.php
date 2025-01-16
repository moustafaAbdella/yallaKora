<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $fillable = ['name','parent_id','logo','position','active'];

    protected $casts = [
        'position' => 'int',
    ];

    public function livetvs()
    {
        return $this->hasMany('App\Models\LivetvGenre');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class , 'parent_id')->where('active', 1)->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
    }


}
