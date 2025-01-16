<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonTeam extends Model
{
    protected $fillable = ['league_id','team_id','season_id'];

    use HasFactory;

    protected $with = ['leagues'];

    public function leagues()
    {
        return $this->hasOne(League::class, 'id', 'league_id');

    }
}
