<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MatcheResource extends JsonResource
{
    //php artisan make:resource MatcheResource
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $timezone = $request->timezone;
        $date = $this->startTime;
        if($timezone != null){
            try {
                $date = Carbon::parse($this->startTime, 'Asia/Riyadh')->setTimezone($timezone);
//                if($timezone == "Asia/Amman"){
//                    $date->addHours(1);
//                }
            } catch (\Exception $e) {
                $date = Carbon::parse($this->startTime, 'Asia/Riyadh');
            }
            $date = $date->format('Y-m-d H:i:s');
        }
        return [
            'id' => $this->id,
            'api_id'=> $this->api_id,
            'team_re_b'=> $this->team_re_b,
            'team_re_a'=> $this->team_re_a,
            'seasonNum' => $this->seasonNum,
            'stageNum' => $this->stageNum,
            'roundNum' => $this->roundNum,
            'competitionDisplayName' => $this->competitionDisplayName,
            'start_time' => $date,
            'statusGroup'=> $this->statusGroup,
            'statusText'=> $this->statusText,
            'shortStatusText'=> $this->shortStatusText,
            'gameTimeAndStatusDisplayType'=> $this->gameTimeAndStatusDisplayType,
            'justEnded' => $this->justEnded,
            'minute' => $this->gameTime,
            'gameTimeDisplay' => $this->gameTimeDisplay,
            'winner' => $this->winner,
            'teamWinner' => $this->teamWinner,
            'is_live' => $this->is_live,
            'hasMissingPlayers' => $this->hasMissingPlayers,
            'hasFieldPositions' => $this->hasFieldPositions,
            'hasStats' => $this->hasStats,
            'hasStandings' => $this->hasStandings,
            'hasBrackets' => $this->hasBrackets,
            'hasPreviousMeetings' => $this->hasPreviousMeetings,
            'hasRecentMatches' => $this->hasRecentMatches,
            'hasBets' => $this->hasBets,
            'hasPlayerBets' => $this->hasPlayerBets,

            'com_id' => $this->com_id,
            'com_name' => $this->comp->name,
            'com_name_ar' => $this->comp->name_ar,
            'com_name_ar_sub' => $this->comp->name_ar_sub,
            'com_img' => $this->comp->img,
            'team_id_a'=> $this->team_id_a,
            'team_a_name'=> $this->team_a->name,
            'team_a_name_ar'=> $this->team_a->name_ar,
            'team_a_img'=> $this->team_a->img,
            'team_id_b'=> $this->team_id_b,
            'team_b_name'=> $this->team_b->name,
            'team_b_name_ar'=> $this->team_b->name_ar,
            'team_b_img'=> $this->team_b->img,
            'team_formation_a'=> $this->team_formation_a,
            'team_formation_b'=> $this->team_formation_a,
            'vi' => $this->videos,
        ];
    }

}
