<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\League;
use App\Models\Season;
use App\Scrape\ScrapeJdwel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LeaguesSeederOld extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ScrapeJdwel $scrapeJdwel)
    {
        $matchToday = $scrapeJdwel->getResponseApi('wp-json/jmanager/v1.3/competitions');
        if($matchToday['state'] == 200){
            $placeholder = false;
            if(!Storage::disk('leagues')->exists('placeholder.png')){
                $image_client = new Client();
                try {
                    $image = $image_client->request('GET', $scrapeJdwel->getDomainUrl() . 'image/teams/' . 'placeholder.png', ['http_errors' => false]);
                    if($image->getStatusCode() == 200){
                        $placeholder = Storage::disk('leagues')->put('placeholder.png', $image->getBody()->getContents()) ? 'placeholder.png' : '';
                    }
                }catch (ClientException $e) {


                }
            }
            foreach ($matchToday['response']['data'] as $League){
                if($League['logo']){
                    if(Storage::disk('leagues')->exists($League['logo'])){
                        $filename = $League['logo'];
                    }else{
                        $image_client = new Client();

                        $image = $image_client->request('GET', $scrapeJdwel->getDomainUrl() . 'image/comp-logo/' . $League['logo'], ['http_errors' => false]);
                        if($image->getStatusCode() == 200){
                            $placeholder2 = $placeholder ? 'placeholder.png' :'';
                            $filename = Storage::disk('leagues')->put($League['logo'], $image->getBody()->getContents()) ? $League['logo'] : $placeholder2;
                        }else{
                            $filename = $placeholder ? 'placeholder.png' :'';
                        }

                        //  $filepath = $request->root() . $League['logo'];
                    }
                }
                League::create([
                    'api_id'             => $League['comp_id'],
                    'current_season'     => $League['current_season'],
                    'featured'           => $League['featured'],
                    'api_slug'           => str_replace(' ', '-', $League['title']),
                    'type'               => $League['type'],
                    'teams_type'         => $League['teams_type'],
                    'img'                => $filename,
                    'iso2'               => $League['iso2'] ?: '',
                    'name'               => $League['title'],
                    'name_ar'            => $League['title_ar'] ?: '',
                    'name_ar_sub'        => $League['title_ar_sub'] ?: '',
                ]);
                $getSeasson = $scrapeJdwel->getResponseApi('wp-json/jmanager/v1/seasons/bycomp/' . $League['comp_id']);
                if($getSeasson['state'] == 200){
                    foreach ($getSeasson['response']['data'] as $season){
                        $checkSeason = Season::where('api_id', $season['season_id'])->get();
                        if (count($checkSeason) < 1) {
                            Season::create([
                                'api_id'       => $season['season_id'],
                                'name'         => $season['the_season'],
                            ]);
                        }
                    }
                }
            }
        }else {
            $matchToday = $scrapeJdwel->competitions();

            foreach ($matchToday['data'] as $League){
               // $this->info($League['comp_id']);
                if($League['logo']){
                    if(Storage::disk('leagues')->exists($League['logo'])){
                        $filename = $League['logo'];
                    }else{
                        $image_client = new Client();
                        $image = $image_client->request('GET', $scrapeJdwel->getDomainUrl() . 'image/comp-logo/' . $League['logo']);
                        $filename = Storage::disk('leagues')->put($League['logo'], $image->getBody()->getContents());
                        //  $filepath = $request->root() . $League['logo'];
                    }
                }
                League::create([
                    'jdwel_id'           => $League['comp_id'],
                    'current_season'     => $League['current_season'],
                    'featured'           => $League['featured'],
                    'api_slug'         => $League['jdwel_slug'],
                    'type'               => $League['type'],
                    'img'                => $filename,
                    'name'               => str_replace('-', ' ', $League['jdwel_slug']),
                    'name_ar'            => $League['title_ar'],
                    'name_ar_sub'        => $League['title_ar_sub'] ?: '',
                ]);
            }

        }
       // if($matchToday->state ==)
    }


}
