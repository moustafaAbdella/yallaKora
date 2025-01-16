<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeameResource extends JsonResource
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
            'name_ar' => $this->name_ar,
            'category' => $this->category,
            'country' => $this->country,
            'country_ar' => $this->country_ar,
            'country_iso2' => $this->country_iso2,
            'founded' => $this->founded,
            'img' => $this->img,
            'type' => $this->type,
            'venue' => $this->venue,
            'venue_ar' => $this->venue_ar,
            'has_favorited' => $this->has_favorited,

        ];
    }
}
