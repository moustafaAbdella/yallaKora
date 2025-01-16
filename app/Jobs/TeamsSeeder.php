<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Team;
use App\Models\League;
use App\Models\Season;
use App\Models\SeasonTeam;
use App\Scrape\ScrapeJdwel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TeamsSeeder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scrapeJdwel = new ScrapeJdwel;
        $placeholder = false;
        if(!Storage::disk('teams')->exists('placeholder.png')){
            $image_client = new Client();
            try {
                $image = $image_client->request('GET', $scrapeJdwel->getDomainUrl() . 'image/teams/' . 'placeholder.png', ['http_errors' => false]);
                if($image->getStatusCode() == 200){
                    $placeholder = Storage::disk('teams')->put('placeholder.png', $image->getBody()->getContents()) ? 'placeholder.png' : '';
                }
            }catch (ClientException $e) {
                $placeholder = $placeholder ? 'placeholder.png' : '';
            }             
        }else {
            $placeholder = $placeholder ? 'placeholder.png' : '';
        }
        foreach ($this->data as $League){ 

            $getTeam = Team::where('api_id', $League['team_id'])->first();

            if($League['logo']){
                $s1 = trim(preg_replace('/\s\s+/', ' ', $League['logo']));

                if(Storage::disk('teams')->exists($s1)){
                    $filename = $League['logo'];
                }else{
                    $image_client = new Client();

                    $image = $image_client->request('GET', $scrapeJdwel->getDomainUrl() . 'image/teams/' . $League['logo'], ['http_errors' => false]);
                    if($image->getStatusCode() == 200){
                        $filename = Storage::disk('teams')->put($League['logo'], $image->getBody()->getContents()) ? $League['logo'] : $placeholder;
                    }else{
                        $filename = $placeholder;
                    }

                    //  $filepath = $request->root() . $League['logo'];
                }
            }else {
                $filename =  $placeholder;
            }
            $team = $scrapeJdwel->getResponseApi('wp-json/jmanager/v1/team/' . $League['team_id']);
            if($team['state'] == 200){
                $team = $team['response']['data'];
                if($getTeam !=null) {
                    $getTeam->api_id  = $team['info']['id'];
                    $getTeam->api     = $team['info']['api'];
                    $getTeam->name    = $team['info']['name'] ?: '';
                    $getTeam->name_ar = $team['info']['name_ar'] ?: '';
                    $getTeam->img     = $filename;
                    $getTeam->city_ar = $team['info']['city_ar'] ?: '';
                    $getTeam->city    = $team['info']['city'] ?: '';
                    $getTeam->founded = $team['info']['founded'] ?: '';
                    $getTeam->type    = $team['info']['type'] ?: '';
                    $getTeam->category= $team['info']['category'] ?: '';
                    $getTeam->country = $team['info']['country'] ?: '';
                    $getTeam->country_ar = $team['info']['country_ar'] ?: '';
                    $getTeam->country_iso2 = $team['info']['country_iso2'] ?: '';
                    $getTeam->venue = $team['info']['venue'] ?: '';
                    $getTeam->venue_ar = $team['info']['venue_ar'] ?: '';
                    $getTeam->save();
                }else {
                    $getTeam = Team::create([
                        'api_id'             => $team['info']['id'],
                        'api'                => $team['info']['api'],
                        'name'               => $team['info']['name'] ?: '',
                        'name_ar'            => $team['info']['name_ar'] ?: '',
                        'img'                => $filename ,
                        'city'               => $team['info']['city'] ?: '',
                        'city_ar'            => $team['info']['city_ar'] ?: '',
                        'founded'            => $team['info']['founded'] ?: '',
                        'type'               => $team['info']['type'] ?: '',
                        'category'           => $team['info']['category'] ?: '',
                        'country'            => $team['info']['country'] ?: '',
                        'country_ar'         => $team['info']['country_ar'] ?: '',
                        'country_iso2'       => $team['info']['country_iso2'] ?: '',
                        'venue'              => $team['info']['venue'] ?: '',
                        'venue_ar'           => $team['info']['venue_ar'] ?: '',
                    ]);
                }

                foreach ($team['current_seasons'] as $teamSeason){ 
                    $getSeason = Season::where('api_id', $teamSeason['season_id'])->first();
                    $getLeague = League::where('api_id', $teamSeason['comp_id'])->first();
                    if ( $getSeason != null && $getLeague != null) {

                        $checkTeam = SeasonTeam::where('season_id', $getSeason->id)->where('team_id', $getTeam->id)->where('league_id', $getLeague->id)->get();

                        if (count($checkTeam) < 1) {                        
                            SeasonTeam::create([
                                'season_id'         => $getSeason->id,
                                'team_id'           => $getTeam->id,
                                'league_id'         => $getLeague->id,
                            ]);   
                        } 
                    }          
                }       
            } else {
                if($getTeam !=null) {
                    $getTeam->api_id  = $League['team_id'];
                    $getTeam->name    = $League['name'] ?: '';
                    $getTeam->name_ar = $League['name_ar'] ?: '';
                    $getTeam->img     = $filename;
                    $getTeam->category= $League['category'] ?: '';
                    $getTeam->save();
                }else{
                    Team::create([
                        'api_id'             => $League['team_id'],
                       // 'api'               => $League['api'],
                        'name'              => $League['name'],
                        'name_ar'           => $League['name_ar'],
                        'category'         => $League['category'],
                        'img'                => $filename,
                    ]);
                }
            }
        }

        return 'will';
    }
}
