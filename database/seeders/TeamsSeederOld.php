<?php

namespace Database\Seeders;

use App\Scrape\Scores365\Scrape365;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\League;
use App\Models\Season;
use App\Models\SeasonTeam;
use App\Scrape\ScrapeJdwel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TeamsSeederOld extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ScrapeJdwel $scrapeJdwel)
    {
        $scrape365 = new Scrape365();
        $teams = $scrape365->getTeams()->competitors;
        $chunckedArray=array_chunk($teams,50);
        foreach ($chunckedArray as $array){
            dispatch(new \App\Jobs\TeamsSeeder($array));
        }

       // if($matchToday->state ==)
    }


}
