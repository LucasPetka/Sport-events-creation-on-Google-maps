<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'place_id' => $this->place_id,
            'title' => $this->title,
            'about' => $this->about,
            'time_from' => $this->time_from,
            'time_until' => $this->time_until,
            'people_going' => $this->people_going,
            'organizator' => $this->organizator

        ];
    }
}
