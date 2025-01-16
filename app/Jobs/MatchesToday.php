<?php

namespace App\Jobs;

use App\Scrape\Scores365\Scrape365;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Team;
use App\Models\Matche;
use App\Models\League;
use App\Models\Season;
use App\Models\SeasonTeam;
use App\Notification\NotificationTe;
use App\Scrape\ScrapeJdwel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Exceptions\CustomException;
use Carbon\Carbon;

class MatchesToday implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $countries;

    public function __construct($data,$countries)
    {
        $this->data = $data;
        $this->countries = $countries;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendChat = new NotificationTe;

            foreach ($this->data as $matche){

                $getTeamA = Team::where('api_id', $matche->homeCompetitor->id)->first();
                $getTeamB = Team::where('api_id', $matche->awayCompetitor->id)->first();
                $getComb = League::where('api_id', $matche->competitionId)->first();

                $scrape365 = new Scrape365();

                if(!$getComb){
                    $getComb = $this->storeComp($scrape365,$matche->competitionId);
                }
                if($getTeamA != null && $getTeamB != null && $getComb){
                    //$sendChat->sendMsgToAdmin("team -> team A my : " . $getTeamA->id . 'team' . $matche['hometeam_id']);
                    //$sendChat->sendMsgToAdmin("team -> team B my : " . $getTeamb->id . 'team' . $matche['awayteam_id']);

                    $this->storeMatche($matche,$getTeamA,$getTeamB,$getComb,$scrape365);
                } else {
                    if(!$getTeamA != null){
//                        $getTeamA = $scrape365->getTeam($matche->homeCompetitor->id);
                        $getTeamA = $this->storeTeam($scrape365,$matche->homeCompetitor);
                    }
                    if(!$getTeamB != null){
//                        $getTeamB = $scrape365->getTeam($matche->awayCompetitor->id);
                        $getTeamB = $this->storeTeam($scrape365,$matche->awayCompetitor);
                    }
                   // $sendChat->sendMsgToAdmin("Create team -> team my : " . json_encode($getTeamA) . 'team' . json_encode($matche));
                   // $sendChat->sendMsgToAdmin("team -> team B my : " . '$getTeamb->id' . 'team' . $matche['awayteam_id']);

                    $this->storeMatche($matche,$getTeamA,$getTeamB,$getComb,$scrape365);

                //     throw new CustomException(' not found team in database ' . ' com_id = '
                //               .  $matche['comp_id'] . ' team_id_a = ' . $matche['hometeam_id'] .
                //               ' team_id_b = ' .$matche['awayteam_id']);
                }


            }
            $sendChat->sendMsgToAdmin('تم تحديث جدول المباريات');


    }

    public function storeComp($scrape365,$comp){
        $response = $scrape365->getLeague($comp);
        $league = $response->body->competitions[0];

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
        foreach ($response->body->countries as $country) {
            if ($country->id == $league->countryId) {
                $country_name = $country->name;
            }
        }

        $scrape365->setLangId(27);
        $responseAr = $scrape365->getLeague($comp);
        $leagueAr = $responseAr->body->competitions[0];
        $league_name_ar = $leagueAr->name;
        $scrape365->setLangId(1);

        return League::create([
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
    }
    public function storeTeam($scrape365,$team){
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
        foreach ($this->countries as $country) {
            if ($country->id == $team->countryId) {
                $country_name = $country->name;
            }
        }

//                $scrape365->setLangId(27);
//                $league_ar = $scrape365->getLeague($league->id);
//                $league_name_ar = $league_ar->body->competitions[0]->name;

        return Team::create([
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
            'name_ar'              => null,
            'img'                  => $filename,
        ]);
    }

    public function storeMatche($matche,$getTeamA,$getTeamb,$getComb,$scrape365){

        $checkMatche = Matche::where('api_id', $matche->id)->first();

//        $getTime = $scrape365->getTimeMatche($matche['match_id']);
//        $triggerOn = $getTime['response'];
//        $user_tz = 'Asia/Riyadh';
        $schedule_date = Carbon::parse($matche->startTime);
        $triggerOn = $schedule_date->format('Y-m-d H:i:s');

        if($checkMatche !=null) {
            $checkMatche->api_id      = $matche->id;
            $checkMatche->team_re_a   = $matche->homeCompetitor->score != -1 ? $matche->homeCompetitor->score :0;
            $checkMatche->team_re_b   = $matche->awayCompetitor->score != -1 ? $matche->awayCompetitor->score :0;
            $checkMatche->team_id_a   = $getTeamA->id;
            $checkMatche->team_id_b   = $getTeamb->id;
            $checkMatche->seasonNum   = $matche->seasonNum??null;
            $checkMatche->stageNum               = $matche->stageNum??null;
            $checkMatche->roundNum               = $matche->roundNum??null;
            $checkMatche->competitionDisplayName = $matche->competitionDisplayName??null;

            $checkMatche->startTime  = $triggerOn??null;
            $checkMatche->statusGroup = $matche->statusGroup?: 0;
            $checkMatche->statusText  = $matche->statusText?: 0;
            $checkMatche->shortStatusText = $matche->shortStatusText?: 0;
            $checkMatche->gameTimeAndStatusDisplayType = $matche->gameTimeAndStatusDisplayType?: 0;
            $checkMatche->justEnded= $matche->justEnded??0;
            $checkMatche->gameTime = $matche->gameTime == -1 || $matche->gameTime == null ? 0 : $matche->gameTime;
            $checkMatche->gameTimeDisplay     = $matche->gameTimeDisplay??null;
            $checkMatche->winner     = $matche->winner??null;
            if($matche->homeCompetitor->score > $matche->awayCompetitor->score){
                $teamWinner = 'a';
            } else {
                $teamWinner = 'b';
            }
            $checkMatche->teamWinner     = $teamWinner??null;

            $checkMatche->hasLineups   = $matche->hasLineups??0;
            $checkMatche->hasMissingPlayers   = $matche->hasMissingPlayers??0;
            $checkMatche->hasFieldPositions   = $matche->hasFieldPositions??0;
            $checkMatche->hasStats   = $matche->hasStats??0;
            $checkMatche->hasStandings   = $matche->hasStandings??0;
            $checkMatche->hasBrackets   = $matche->hasBrackets??0;
            $checkMatche->hasPreviousMeetings   = $matche->hasPreviousMeetings??0;
            $checkMatche->hasRecentMatches   = $matche->hasRecentMatches??0;
            $checkMatche->hasBets   = $matche->hasBets??0;
            $checkMatche->hasPlayerBets   = $matche->hasPlayerBets??0;
            $checkMatche->com_id      = $getComb->id??0;
            $checkMatche->save();
        }else {
            $checkMatche = new Matche();
            $checkMatche->api_id      = $matche->id;
            $checkMatche->team_re_a   = $matche->homeCompetitor->score != -1 ? $matche->homeCompetitor->score :0;
            $checkMatche->team_re_b   = $matche->awayCompetitor->score != -1 ? $matche->awayCompetitor->score :0;
            $checkMatche->team_id_a   = $getTeamA->id;
            $checkMatche->team_id_b   = $getTeamb->id;
            $checkMatche->seasonNum   = $matche->seasonNum??null;
            $checkMatche->stageNum               = $matche->stageNum??null;
            $checkMatche->roundNum               = $matche->roundNum??null;
            $checkMatche->competitionDisplayName = $matche->competitionDisplayName??null;

            $checkMatche->startTime  = $triggerOn??null;
            $checkMatche->statusGroup = $matche->statusGroup?: 0;
            $checkMatche->statusText  = $matche->statusText?: 0;
            $checkMatche->shortStatusText = $matche->shortStatusText?: 0;
            $checkMatche->gameTimeAndStatusDisplayType = $matche->gameTimeAndStatusDisplayType?: 0;
            $checkMatche->justEnded= $matche->justEnded??0;
            $checkMatche->gameTime = $matche->gameTime == -1 || $matche->gameTime == null ? 0 : $matche->gameTime;
            $checkMatche->gameTimeDisplay     = $matche->gameTimeDisplay??null;
            $checkMatche->winner     = $matche->winner??null;
            if($matche->homeCompetitor->score > $matche->awayCompetitor->score){
                $teamWinner = 'a';
            } else {
                $teamWinner = 'b';
            }
            $checkMatche->teamWinner     = $teamWinner??null;

            $checkMatche->hasLineups   = $matche->hasLineups??0;
            $checkMatche->hasMissingPlayers   = $matche->hasMissingPlayers??0;
            $checkMatche->hasFieldPositions   = $matche->hasFieldPositions??0;
            $checkMatche->hasStats   = $matche->hasStats??0;
            $checkMatche->hasStandings   = $matche->hasStandings??0;
            $checkMatche->hasBrackets   = $matche->hasBrackets??0;
            $checkMatche->hasPreviousMeetings   = $matche->hasPreviousMeetings??0;
            $checkMatche->hasRecentMatches   = $matche->hasRecentMatches??0;
            $checkMatche->hasBets   = $matche->hasBets??0;
            $checkMatche->hasPlayerBets   = $matche->hasPlayerBets??0;
            $checkMatche->com_id      = $getComb->id??0;
            $checkMatche->save();
        }
//        $this->info('');
    }

    public function failed($exception)
    {
        $sendChat = new NotificationTe;

        $exception->getMessage();

//        $sendChat->sendMsgToAdmin("Create team -> team a : " . json_encode($teama) . 'team b :' . json_encode($teamb) . 'team' . json_encode($matche));

        // etc...
    }
}
