<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeclinedPlaces extends Model
{
    protected $table = 'declined_places';

    function typee() {
        return $this->belongsTo('App\Type', 'type', 'id');
    }
}
