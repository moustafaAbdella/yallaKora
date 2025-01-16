<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Notification\NotificationTe;

class Matche extends Model
{

    protected $guarded = [];  

    //protected $with = ['team_b','comp', 'videos','season'];

    protected $appends = ['team_a','team_b','comp','videos','season'];

    use HasFactory;

    public function team_a_db()
    {
        return $this->hasOne(Team::class, 'id', 'team_id_a');
    }

    // public static function getRoleCacheKey(Matche $match): string
    // {
    //     return sprintf('team_a_id', $match->id);
    // }

    // Define accessor for caching purposes
    public function getTeamAAttribute(): Team
    {
        if ($this->relationLoaded('team_a_db')) {
            return $this->getRelationValue('team_a_db');
        }
        
        // Replace 3600 for the amount of seconds you would like to cache
        $team = Cache::remember('team_a_id_'. $this->team_id_a, 3600, function () {
            return $this->getRelationValue('team_a_db');
        });

        $this->setRelation('team_a_db', $team);

        return  $team;
    }

    public function team_b_db()
    {
        return $this->hasOne(Team::class, 'id', 'team_id_b');
    }

    public function getTeamBAttribute(): Team
    {
        if ($this->relationLoaded('team_b_db')) {
            return $this->getRelationValue('team_b_db');
        }
        
        // Replace 3600 for the amount of seconds you would like to cache
        $team = Cache::remember('team_b_id_'. $this->team_id_b, 3600, function () {
            return $this->getRelationValue('team_b_db');
        });

        $this->setRelation('team_b_db', $team);

        return  $team;
    }
    
    public function comp()
    {
        return $this->hasOne(League::class, 'id', 'com_id');
    }

    public function getCompAttribute(): League
    {
        if ($this->relationLoaded('comp')) {
            return $this->getRelationValue('comp');
        }
        
        // Replace 3600 for the amount of seconds you would like to cache
        $team = Cache::remember('comp_id_'. $this->com_id , 3600, function () {
            return $this->getRelationValue('comp');
        });

        $this->setRelation('comp', $team);

        return  $team;
    }

    // public function videos()
    // {
    //     return $this->hasMany(MatcheVideo::class);
    // }

    public function getVideosAttribute()
    {
        // if ($this->relationLoaded('videos')) {
        //     return $this->getRelationValue('videos');
        // }
        
        // // Replace 3600 for the amount of seconds you would like to cache
        // $team = Cache::remember('videos_id_'. $this->id , 3600, function () {
        //     return $this->getRelationValue('videos');
        // });

        // $this->setRelation('videos', $team);

        return [];
    }

    public function season()
    {
        return $this->hasOne(Season::class, 'id', 'season_id');
    }

    public function getSeasonAttribute()
    {
        if ($this->relationLoaded('season')) {
            return $this->getRelationValue('season');
        }
        
        // Replace 3600 for the amount of seconds you would like to cache
        $team = Cache::remember('seasons_id_'. $this->season_id , 3600, function () {
            return $this->getRelationValue('season');
        });

        $this->setRelation('season', $team);

        return  $team;
    }
    
}
