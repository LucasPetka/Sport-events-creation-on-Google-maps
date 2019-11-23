<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaceQueue;
use App\Place;
use App\Type;

class AdminController extends Controller
{
    public function index()
    { 
        $places = PlaceQueue::all();
        $types = Type::all();

        return view('admin')->with(compact('places', 'types'));
    }

    public function acceptPlace($id)
    { 
        $place = PlaceQueue::find($id);

        $newPlace = new Place;
        $newPlace->title = $place->title;
        $newPlace->about = $place->about;
        $newPlace->lat = $place->lat;
        $newPlace->lng = $place->lng;
        $newPlace->type = $place->type;
        $newPlace->created_at = $place->created_at;
        $newPlace->updated_at = $place->updated_at;
        $newPlace->save();

        $place->delete();

        return redirect('/admin');
    }

    public function declinePlace($id)
    { 
        $place = PlaceQueue::find($id);
        $place->delete();

        return redirect('/admin');
    }

}
