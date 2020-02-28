<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Place extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return[
            'id' => $this->id,
            'title' => $this->title,
            'about' => $this->about,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'type' => $this->type,
            'paid' => $this->paid,
            'highlighted' => $this->highlighted,

        ];
    }


    public function with($request){
        return[
            'version' => '1.0.0',
            'author_url' => url('http://apartamentaikaunas.lt')
        ];
    }

}

