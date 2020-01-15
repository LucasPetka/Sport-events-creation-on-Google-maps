<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Place;

use App\Event;
use App\Http\Resources\Event as EventResource;

use App\PeopleGoing;
use App\Http\Resources\PeopleGoing as PeopleGoingResource;

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
        $events = DB::table('events')->select('*')->where('person_id','=',$user->id)->get();
        $places_arr = [];

        foreach ($events as $key => $event) {
            array_push($places_arr, $event->place_id);
        }
        
        
        $going = DB::table('people_going')->select('*')->where('person_id','=',$user->id)->get();
        $event_arr = [];
        foreach ($going as $key => $event) {
            array_push($event_arr, $event->event_id);
            array_push($places_arr, $event->place_id);
        }
        $goingtoevents = DB::table('events')->select('*')
        ->whereIn('id', $event_arr)
        ->get();

        //$places_arr = array_unique($places_arr);

        $places = Place::select('*')
        ->whereIn('id', $places_arr)
        ->get();



        return view('home')->with(compact('user', 'events', 'goingtoevents', 'places'));
    }
}
