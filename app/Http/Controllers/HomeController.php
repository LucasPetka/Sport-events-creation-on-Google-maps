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
        $createdevents = Event::where('person_id','=',$user->id)->get();
        $places_arr = [];

        foreach ($createdevents as $key => $event) {
            array_push($places_arr, $event->place_id);
        }
    
        $going = DB::table('people_going')->select('*')->where('person_id','=',$user->id)->get();

        $event_arr = [];
        foreach ($going as $key => $event) {
            array_push($event_arr, $event->event_id);
            array_push($places_arr, $event->place_id);
        }

        $goingtoevents = Event::whereIn('id', $event_arr)->get();

        $accepted = AcceptedPlaces::select('place_id')
        ->where('person_id','=',$user->id)->get()->toArray();
        $accepted_places = Place::select('*')
        ->whereIn('id', $accepted)
        ->get();

        $declined_places = DeclinedPlaces::with('typee')->where('personid','=',$user->id)->get();

        $submited_places = PlaceQueue::select('*')
        ->where('personid','=',$user->id)->get();

        $types = Type::all();



        return view('home')->with(compact('user', 'createdevents', 'goingtoevents', 'accepted_places', 'submited_places', 'declined_places', 'types'));
    }



    function update_profile(Request $request){

        $user = Auth::user();
        $user->name = $request->input('username');
        $user->liked_sports = json_encode($request->input('types'));

        $user->save();

        return redirect('/home');
    }

}
