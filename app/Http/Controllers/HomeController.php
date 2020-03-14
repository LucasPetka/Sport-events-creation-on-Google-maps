<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Place;
use App\User;
use App\Type;
use App\AcceptedPlaces;
use App\PlaceQueue;
use App\Event;
use App\PeopleGoing;
use App\DeclinedPlaces;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    //User profile load up
    public function index()
    {
        $user = Auth::user();

        $types = Type::all();

        return view('home')->with(compact('user','types'));
    }


    function returnCreatedEvents(){
        $user = Auth::user();
        $createdevents = DB::table('events')->where('person_id','=',$user->id)
        ->join('places', 'places.id', '=', 'events.place_id')
        ->join('types', 'types.id', '=', 'places.type')
        ->select('events.*', 'types.image', 'places.lat', 'places.lng')
        ->get();
        return $createdevents->toJson();
    }

    function returnGoingToEvents(){
        $user = Auth::user();
        $goingtoevents = DB::table('people_going')->where('people_going.person_id','=',$user->id)
        ->join('events', 'events.id', '=', 'people_going.event_id')
        ->join('places', 'places.id', '=', 'people_going.place_id')
        ->join('types', 'types.id', '=', 'places.type')
        ->select('events.*', 'types.image', 'places.lat', 'places.lng')
        ->get();
        return $goingtoevents->toJson();
    }

    function returnAcceptedPlaces(){
        $user = Auth::user();
        $acceptedPlaces = DB::table('accepted_places')
        ->where('accepted_places.person_id','=',$user->id)
        ->join('places', 'places.id', '=', 'accepted_places.place_id')
        ->join('types', 'types.id', '=', 'places.type')
        ->select('places.*', 'types.image')
        ->get();
        return $acceptedPlaces->toJson();
    }

    function returnDeclinedPlaces(){
        $user = Auth::user();
        $declined_places = DB::table('declined_places')->where('declined_places.personid','=',$user->id)
        ->join('types', 'types.id', '=', 'declined_places.type')
        ->select('declined_places.*', 'types.image')
        ->get();
        return $declined_places->toJson();
    }

    function returnSubmitedPlaces(){
        $user = Auth::user();
        $submited_places = DB::table('placequeue')->where('placequeue.personid','=',$user->id)
        ->join('types', 'types.id', '=', 'placequeue.type')
        ->select('placequeue.*', 'types.image')
        ->get();

        return $submited_places->toJson();
    }



    function update_profile(Request $request){

        $user = Auth::user();
        $user->name = $request->input('username');
        $user->liked_sports = json_encode($request->input('types'));

        $user->save();

        return redirect('/home');
    }

}
