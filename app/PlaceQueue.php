<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;

class PlaceQueue extends Model
{
    protected $table = 'placequeue';

    function user() {
        return $this->belongsTo('App\User', 'personid', 'id');
    }

    function typee() {
        return $this->belongsTo('App\Type', 'type', 'id');
    }
}
