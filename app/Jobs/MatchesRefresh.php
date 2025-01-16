<?php

namespace App\Jobs;

use App\Scrape\Scores365\Scrape365;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Matche;
use App\Models\Season;
use App\Scrape\ScrapeJdwel;
use App\Notification\NotificationTe;
use App\Notification\NotificationFb;
use App\Models\Notifications;

class MatchesRefresh implements ShouldQueue
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

    private function _GenerateTeam1VsTeam2Msg($team1, $team2)
    {
        $msg = ' team_a ضد team_b ';
        $msg = str_replace($team1 ,$msg);
        $msg = str_replace($team2 ,$msg);
        return $msg;
    }

    private function _GenerateGoalMsg($team1, $team1Score, $team2, $team2Score)
    {
        $template = ':teamHome [:team1Score] - :teamAway :team2Score';

        $msg = str_replace(':teamHome', $team1, $template);
        $msg = str_replace(':teamAway', $team2, $msg);
        $msg = str_replace(':team1Score', $team1Score, $msg);
        $msg = str_replace(':team2Score', $team2Score, $msg);

        return $msg;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scrape365 = new Scrape365();
        $date = Carbon::now();
        $formatedDate = $date->format('d-m-Y');
        $liveMatchesGamesToday = $scrape365->getMatches(startDate: $formatedDate,endDate: $formatedDate,isLive: false);
        // Filter the collection based on the 'id' values in the $idsToFilter array
//        $filteredMatches = collect($liveMatchesGamesToday)->whereIn('id', $idsToFilter)->first();

        //info($filteredData);

        $fireBaseNotification = new NotificationFb;
        // $sendChat = new NotificationTe;

        foreach ($this->data as $item) {
            $isFound = false;
            foreach ($liveMatchesGamesToday->body->games as $match) {
                if ($match->id == $item->api_id)
                {
                    $isFound = true;
                    $this->checkMatch($fireBaseNotification,$match,$item);
                }
            }
            if (!$isFound){
                $match = $scrape365->getMatchDetails($item->api_id);
                $this->checkMatch($fireBaseNotification,$match->body->game,$item);
            }
        }
    }

    private function checkMatch(NotificationFb $fireBaseNotification,$match,$item)
    {
//        if (!empty($match) && $match != 0) {
            if ($match->statusText == "Ended" && $item->statusText != "Ended") {
                $msg = $this->_GenerateTeam1VsTeam2Msg($match->homeCompetitor->name, $match->awayCompetitor->name);
                $fireBaseNotification->sendMsgToFavourite("انتهاء المباراة!" , $msg,$item->com_id,$item->team_id_a ,$item->team_id_b,"whistle.caf");
                // $sendChat->sendMsgToAdmin($msg);

            } elseif ($match->statusText == "Cancelled" && $item->statusText != "Cancelled") {
                $msg = $this->_GenerateTeam1VsTeam2Msg($match->homeCompetitor->name, $match->awayCompetitor->name);
                $fireBaseNotification->sendMsgToFavourite('انتهاء المباراة!',$msg,$item->com_id,$item->team_id_a ,$item->team_id_b,"whistle.caf");

                $fireBaseNotification->sendMsgToFavourite("الغاء المباراة!" , $msg,$item->com_id,$item->team_id_a ,$item->team_id_b,"whistle.caf");
                // $sendChat->sendMsgToAdmin($msg);
            } elseif (($match->homeCompetitor->score > 0 && $match->homeCompetitor->score > $item->team_re_a)) {
                $msg = $this->_GenerateGoalMsg($match->homeCompetitor->name, $match->homeCompetitor->score, $match->awayCompetitor->name, $match->awayCompetitor->score);
                $fireBaseNotification->sendMsgToFavourite(" هدف! ", $msg,$item->com_id,$item->team_id_a ,$item->team_id_b,"goal.caf");
                // $sendChat->sendMsgToAdmin($msg);
            } elseif (($match->awayCompetitor->score > 0 && $match->awayCompetitor->score > $item->team_re_b)) {
                $msg = $this->_GenerateGoalMsg($match->awayCompetitor->name, $match->awayCompetitor->score, $match->homeCompetitor->name, $match->homeCompetitor->score);
                $fireBaseNotification->sendMsgToFavourite(" هدف! ", $msg,$item->com_id,$item->team_id_a ,$item->team_id_b,"goal.caf");
                // $sendChat->sendMsgToAdmin($msg);
            }
            $item->team_re_a   = $match->homeCompetitor->score != -1 ? $match->homeCompetitor->score :0;
            $item->team_re_b   = $match->awayCompetitor->score != -1 ? $match->awayCompetitor->score :0;
            $item->statusGroup = $match->statusGroup?: 0;
            $item->statusText  = $match->statusText?: 0;
            $item->shortStatusText = $match->shortStatusText?: 0;
            $item->gameTimeAndStatusDisplayType = $match->gameTimeAndStatusDisplayType?: 0;
            $item->justEnded= $match->justEnded??0;
            $item->gameTime = $match->gameTime == -1 || $match->gameTime == null ? 0 : $match->gameTime;
            $item->gameTimeDisplay     = $match->gameTimeDisplay??null;
            $item->winner     = $match->winner??null;
            if($match->homeCompetitor->score > $match->awayCompetitor->score){
                $teamWinner = 'a';
            } else {
                $teamWinner = 'b';
            }
            $item->teamWinner     = $teamWinner??null;

            $item->hasLineups   = $match->hasLineups??0;
            $item->hasMissingPlayers   = $match->hasMissingPlayers??0;
            $item->hasFieldPositions   = $match->hasFieldPositions??0;
            $item->hasStats   = $match->hasStats??0;
            $item->hasStandings   = $match->hasStandings??0;
            $item->hasBrackets   = $match->hasBrackets??0;
            $item->hasPreviousMeetings   = $match->hasPreviousMeetings??0;
            $item->hasRecentMatches   = $match->hasRecentMatches??0;
            $item->hasBets   = $match->hasBets??0;
            $item->hasPlayerBets   = $match->hasPlayerBets??0;
            $item->save();
//        }

    }
}
