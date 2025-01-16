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

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Scrape365 $scrape365)
    {
        $matchToday = $scrape365->getTeams();
        $scrape365->setLangId(27);
        $matchTodayAr = $scrape365->getTeams();

//        https://imagecache.365scores.com/image/upload/f_png,c_limit,q_auto:eco,dpr_3,d_Competitors:default1.png/v5/Competitors/8201
        if($matchToday->status) {
            foreach ($matchToday->body->competitors as $key => $team) {

                $filename = $team->id . '.png';
                if(!Storage::disk('teams')->exists($filename)){
                    try {
                        $image_client = new Client();
                        $image = $image_client->request('GET', $scrape365->getDomainImageUrl() . '/image/upload/f_png,c_limit,q_auto:eco,dpr_2,d_Competitors:default1.png/v'.$team->imageVersion.'/Competitors/' . $team->id, [
                            'verify' => false
                        ]);
                        $filename = Storage::disk('teams')->put($filename, $image->getBody()->getContents());
                        $filename = $team->id . '.png';
                    } catch (\Exception $exception) {
                        $this->command->error( 'in ' . $team->id . ':'. $team->name  . ' '.$exception->getMessage());
                        $filename = null;
                    }
                }

                $country_name = null;
                foreach ($matchToday->body->countries as $country) {
                    if ($country->id == $team->countryId) {
                        $country_name = $country->name;
                    }
                }

//                $scrape365->setLangId(27);
//                $league_ar = $scrape365->getLeague($league->id);
//                $league_name_ar = $league_ar->body->competitions[0]->name;
                $team_name_ar = null;
                if ($team->id == $matchTodayAr->body->competitors[$key]->id){
                    $team_name_ar = $matchTodayAr->body->competitors[$key]->name;
                } else {
                    foreach ($matchTodayAr->body->competitors as $teamAr) {
                        if ($team->id == $teamAr->id) {
                            $team_name_ar = $teamAr->name;
                            break;
                        }
                    }
                }
//              type :  {team:1,nationalTeam:2,athlete:4,double:8}
                $find = Team::where('api_id','=',$team->id)->first();
                if ($find){
                    $find->update([
                        'api_id'               => $team->id,
                        'competitorNum'        => $team->competitorNum?? null,
                        'longName'             => $team->longName?? null,
                        'mainCompetitionId'    => $team->mainCompetitionId?? null,
                        'nameForURL'           => $team->nameForURL,
                        'color'                => $team->color ?? null,
                        'country'              => $country_name,
                        'popularityRank'       => $team->popularityRank?? null,
                        'name'                 => $team->name,
                        'symbolicName'         => $team->symbolicName?? null,
                        'type'                 => $team->type,
                        'name_ar'              => $team_name_ar,
                        'img'                  => $filename,
                    ]);
                    $this->command->warn('done update '.$team->id.' : ' . $team->name);

                }else {
                    Team::create([
                        'api_id'               => $team->id,
                        'competitorNum'        => $team->competitorNum?? null,
                        'longName'             => $team->longName?? null,
                        'mainCompetitionId'    => $team->mainCompetitionId?? null,
                        'nameForURL'           => $team->nameForURL,
                        'color'                => $team->color ?? null,
                        'country'              => $country_name,
                        'popularityRank'       => $team->popularityRank?? null,
                        'name'                 => $team->name,
                        'symbolicName'         => $team->symbolicName?? null,
                        'type'                 => $team->type?? null,
                        'name_ar'              => $team_name_ar,
                        'img'                  => $filename,
                    ]);

                    $this->command->info('done add '.$team->id.' : ' . $team->name);
                }
            }
        }else {
//            throw new \Exception(json_encode($matchToday->data));
            $this->command->error('error' . 'error');

        }

       // if($matchToday->state ==)
    }


}
