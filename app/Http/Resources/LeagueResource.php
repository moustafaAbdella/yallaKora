<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeagueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'current_season' => $this->current_season,
            'featured' => $this->featured,
            'type' => $this->type,
            'teams_type' => $this->teams_type,
            'name_ar' => $this->name_ar,
            'name_ar_sub' => $this->name_ar_sub,
            'img' => $this->img,
            'has_favorited' => $this->has_favorited,
        ];
    }
}
