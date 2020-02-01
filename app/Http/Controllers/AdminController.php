<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaceQueue;
use App\Place;
use App\AcceptedPlaces;
use App\DeclinedPlaces;
use App\Type;
use App\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    { 
        $places = PlaceQueue::all();

        return view('admin')->with(compact('places'));
    }

    public function places()
    { 
        $places = PlaceQueue::all();

        return view('admin.places')->with(compact('places'));
    }

    public function sportTypes()
    { 
        $types = Type::all();
        $places = PlaceQueue::all();

        return view('admin.sportTypes')->with(compact('types','places'));
    }


    public function users()
    { 
        $users = User::all();
        $places = PlaceQueue::all();

        return view('admin.users')->with(compact('users', 'places'));
    }

    public function deleteUser($id)
    { 
        $user = User::find($id);
        $user->delete();

        return redirect('admin/users');
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

        $accepted_place = new AcceptedPlaces;
        $accepted_place->person_id = $place->personid;
        $accepted_place->place_id = $newPlace->id;
        $accepted_place->save();


        $place->delete();

        return redirect('/admin/places');
    }

    public function declinePlace($id)
    { 
        $place = PlaceQueue::find($id);
        
        $declinedPlace = new DeclinedPlaces;
        $declinedPlace->title = $place->title;
        $declinedPlace->about = $place->about;
        $declinedPlace->lat = $place->lat;
        $declinedPlace->lng = $place->lng;
        $declinedPlace->type = $place->type;
        $declinedPlace->personid = $place->personid;
        $declinedPlace->save();
        
        
        $place->delete();

        return redirect('/admin/places');
    }


}
