<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Team extends Model
{
    use Favoriteable , HasFactory;

    protected $fillable = ['api_id','api','name','name_ar','img','city','city_ar','founded','featured', 'position','type','category'
    ,'country','country_ar','country_iso2','venue','venue_ar'];

    protected $with = ['season'];

    public function season()
    {
        return $this->hasMany(SeasonTeam::class);
    }
}
