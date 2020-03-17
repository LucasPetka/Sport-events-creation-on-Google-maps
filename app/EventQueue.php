<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventQueue extends Model
{
    protected $table = 'eventqueue';

    function user() {
        return $this->belongsTo('App\User', 'person_id', 'id');
    }

    function place() {
        return $this->belongsTo('App\Place', 'place_id', 'id');
    }
}
