<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;

class Event extends Model
{
    

    function user() {
        return $this->belongsTo('App\User', 'person_id', 'id');
    }

    function place() {
        return $this->belongsTo('App\Place', 'place_id', 'id');
    }
}
