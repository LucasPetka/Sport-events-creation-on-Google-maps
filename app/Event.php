<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;

class Event extends Model
{
    

    function place() {
        return $this->belongsTo('App\Place', 'place_id', 'id');
    }
}
