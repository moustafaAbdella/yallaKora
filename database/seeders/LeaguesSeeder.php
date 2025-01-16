<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\League;
use App\Models\Season;
use App\Scrape\Scores365\Scrape365;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LeaguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Scrape365 $scrape365)
    {
        $matchToday = $scrape365->getLeagues();
        $scrape365->setLangId(27);
        $matchTodayAr = $scrape365->getLeagues();

        //https://imagecache.365scores.com/image/upload/f_png,c_limit,q_auto:eco,dpr_3,d_Countries:Round:1.png/v5/Competitions/light/5
        if($matchToday->status) {
            foreach ($matchToday->body->competitions as $key => $league) {

                $filename = $league->id . '.png';
                if(!Storage::disk('leagues')->exists($filename)){
                    try {
                        $image_client = new Client();
                        $image = $image_client->request('GET', $scrape365->getDomainImageUrl() . '/image/upload/f_png,c_limit,q_auto:eco,dpr_3,d_Countries:Round:'.$league->countryId.'.png/v'.$league->imageVersion.'/Competitions/light/' . $league->id, [
                            'verify' => false
                        ]);
                        $filename = Storage::disk('leagues')->put($filename, $image->getBody()->getContents());
                        $filename = $league->id . '.png';
                    } catch (\Exception $exception) {
                        $this->command->error( 'in ' . $league->id . ':'. $league->name  . ' '.$exception->getMessage());
                        $filename = null;
                    }
                }

                $country_name = null;
                foreach ($matchToday->body->countries as $country) {
                    if ($country->id == $league->countryId) {
                        $country_name = $country->name;
                    }
                }

//                $scrape365->setLangId(27);
//                $league_ar = $scrape365->getLeague($league->id);
//                $league_name_ar = $league_ar->body->competitions[0]->name;
                $league_name_ar = null;
                if ($league->id == $matchTodayAr->body->competitions[$key]->id){
                    $league_name_ar = $matchTodayAr->body->competitions[$key]->name;
                }else {
                    foreach ($matchTodayAr->body->competitions as $leagueAr) {
                        if ($league->id == $leagueAr->id) {
                            $league_name_ar = $leagueAr->name;
                            break;
                        }
                    }
                }

                $find = League::where('api_id','=',$league->id)->first();
                if ($find){
                    $find->update([
                        'api_id'           => $league->id,
                        'currentSeasonNum'     => $league->currentSeasonNum?? null,
                        'currentStageNum'      => $league->currentStageNum?? null,
                        'popularityRank'       => $league->popularityRank?? null,
                        'api_slug'             => $league->nameForURL,
                        'color'                => $league->color ?? null,
                        'country_name'         => $country_name,
                        'competitorsType'      => $league->competitorsType,
                        'name'                 => $league->name,
                        'name_ar'              => $league_name_ar,
                        'img'                  => $filename,
                    ]);
                    $this->command->warn('done update '.$league->id.' : ' . $league->name);

                }else {
                    League::create([
                        'api_id'           => $league->id,
                        'currentSeasonNum'     => $league->currentSeasonNum?? null,
                        'currentStageNum'      => $league->currentStageNum?? null,
                        'popularityRank'       => $league->popularityRank?? null,
                        'api_slug'             => $league->nameForURL,
                        'color'                => $league->color ?? null,
                        'country_name'         => $country_name,
                        'competitorsType'      => $league->competitorsType,
                        'name'                 => $league->name,
                        'name_ar'              => $league_name_ar,
                        'img'                  => $filename,
                    ]);

                    $this->command->info('done add '.$league->id.' : ' . $league->name);
                }
            }
        }else {
//            throw new \Exception(json_encode($matchToday->data));
            $this->command->error('error' . 'error');

        }

       // if($matchToday->state ==)
    }


}
