<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\PeopleGoing;
use App\Type;

class EventFinder extends JsonResource
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
            'person_id' => User::findOrFail($this->person_id),
            'lat' => $this->lat,
            'lng' => $this->lng,
            'type' => Type::findOrFail($this->type),
            'people_going' => $this->count

        ];
    }
}
