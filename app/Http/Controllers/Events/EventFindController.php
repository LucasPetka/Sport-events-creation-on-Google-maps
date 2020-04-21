<?php

namespace App\Http\Controllers\Events;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Http\Resources\EventFinder as EventResource;

use App\PeopleGoing;
use App\Http\Resources\PeopleGoing as PeopleGoingResource;
use Auth;
use DB;
use App\Place;
use App\Http\Resources\Place as PlaceResource;
use App\Events\WebsocketDemoEvent;
use Torann\GeoIP\Facades\GeoIP;


class EventFindController extends Controller
{
    public function index()
    {
        $geoip = geoip()->getClientIP();

        if($geoip == '127.0.0.0'){
            $geoip = geoip()->getLocation('212.117.25.184');
        }else{
            $geoip = geoip()->getLocation($geoip);
        }
        
        $location = '{lat:'.$geoip->lat . ", lng:".$geoip->lon.'}';
        
        
        return view('pages.event_finder')->with(compact('location'));
    }


    public function findEvents(Request $request){

        $distance = $request->distance;
        $lat = $request->lat;
        $lng = $request->lng;
        $sportTypes = $request->types;
        $from = $request->date_from;
        $until = $request->date_until;
        $people_going = $request->people_going;

        if($request->paid == true){
            $paid = '1';
        }
        else{
            $paid = '0';
        }


        if(count($sportTypes) != 0){
            $places = DB::table('places')->select('*')
            ->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
            ->whereIn('type', $sportTypes)
            //->where('paid', $paid)
            ->get();
        }
        else{
            $places = DB::table('places')->select('*')
            ->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
            //->where('paid', $paid)
            ->get();
        }

        $places_arr = [];

        foreach ($places as $key => $place) {
            array_push($places_arr, $place->id);
        }

        $events = DB::table('events')->whereIn('events.place_id', $places_arr)
        ->where('time_from', '>' , $from)
        ->where('time_from', '<' , $until)
        ->join('places', 'places.id', '=', 'events.place_id')
        ->leftJoin('people_going', 'events.id', '=', 'people_going.event_id')
        ->groupBy('events.id')
        ->select('events.*', 'places.lat', 'places.lng', 'places.type', DB::raw('count(people_going.event_id) as count'))
        ->having('count', '>' , $people_going)
        ->get();


        return EventResource::collection($events);
    }

    public function recommendedEvents(Request $request){

        $status = $request->status;
        $lat = $request->lat;
        $lng = $request->lng;
        $liked_sports = json_decode($request->liked_sports);
        $date = $request->date;
        $user_id = $request->user_id;
        $distance = 10;

        if(count($liked_sports) != 0){
            $places = DB::table('places')->select('*')
            ->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
            ->whereIn('type', $liked_sports)
            ->get();
        }
        else{
            $places = DB::table('places')->select('*')
            ->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
            ->get();
        }

        $places_arr = [];

        foreach ($places as $key => $place) {
            array_push($places_arr, $place->id);
        }

        $events = DB::table('events')->whereIn('events.place_id', $places_arr)
        ->where('time_from', '>' , $date)
        ->where('events.person_id', '!=', $user_id)
        ->join('places', 'places.id', '=', 'events.place_id')
        ->leftJoin('people_going', 'events.id', '=', 'people_going.event_id')
        ->groupBy('events.id')
        ->select('events.*', 'places.lat', 'places.lng', 'places.type', DB::raw('count(people_going.event_id) as count'))
        ->orderBy('events.time_from', 'asc')
        ->orderBy('count', 'desc')
        ->get();




        return EventResource::collection($events);
    }


}
