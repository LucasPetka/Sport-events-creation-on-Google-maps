<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;

class Place extends Model
{
    


    function typee() {
        return $this->belongsTo('App\Type', 'type', 'id');
    }
}
