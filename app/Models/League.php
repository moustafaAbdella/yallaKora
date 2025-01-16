<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class League extends Model
{
    use Favoriteable , HasFactory;

    protected $fillable = ['id','api_id','api_slug','name','name_ar','img','featured', 'position','name_ar_sub','current_season','type','teams_type'
    ,'iso2'];

}
