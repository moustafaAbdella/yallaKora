<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Livetv extends Model


{

    use Favoriteable;

    protected $fillable = ['name', 'overview', 'logo', 'poster_path', 'backdrop_path', 'link','vip','active','position','featured','embed','hls'];

    protected $with = ['videos'];


    protected $appends = ['genreslist'];


    protected $casts = [
        'featured' => 'int',
        'embed' => 'int',
        'status' => 'int',
        'live' => 'int',
        'active' => 'int',
        'vip' => 'int',
        'hls' => 'int',
        'views' => 'int'
    ];


    public function genres()
    {
        return $this->belongsToMany(Category::class, 'livetv_genres')->withPivot('id');
    }
    // public function genres()
    // {
    //     return $this->hasMany('App\Models\LivetvGenre');
    // }


    public function videos()
    {
        return $this->hasMany('App\Models\LivetvVideo');
    }


    public function getGenreslistAttribute()
    {
        $genres = [];
        foreach ($this->genres as $genre) {
            array_push($genres, $genre['name']);
        }
        return $genres;
    }


}
