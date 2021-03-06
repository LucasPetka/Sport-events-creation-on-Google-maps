<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Place;
use App\User;
use App\Type;
use App\AcceptedPlaces;
use App\PlaceQueue;
use App\Event;
use App\PeopleGoing;
use App\DeclinedPlaces;
use App\EventQueue;
use App\DeclinedEvents;


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

        $places_uploaded = AcceptedPlaces::where('person_id', $user->id)->count();

        $geoip = geoip()->getClientIP();

        if($geoip == '127.0.0.0'){
            $geoip = geoip()->getLocation('212.117.25.184');
        }else{
            $geoip = geoip()->getLocation($geoip);
        }
        
        $location = '{lat:'.$geoip->lat . ", lng:".$geoip->lon.'}';

        return view('home')->with(compact('user','types','location','places_uploaded'));
    }

    //===================================================================================
    //                      ALL DATA RETURN FOR USER PROFILE                  ***START***
    //===================================================================================
    function returnCreatedEvents(){
        $user = Auth::user();
        $createdevents = DB::table('events')->where('events.person_id','=',$user->id)
        ->join('places', 'places.id', '=', 'events.place_id')
        ->join('types', 'types.id', '=', 'places.type')
        ->leftJoin('people_going', 'events.id', '=', 'people_going.event_id')
        ->groupBy('events.id')
        ->select('events.*', 'types.image', 'places.lat', 'places.lng', DB::raw('count(people_going.event_id) as people_going'))
        ->orderBy('time_from', 'asc')
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
        ->orderBy('time_from', 'asc')
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

    function returnDeclinedEvents(){
        $user = Auth::user();
        $declined_events = DB::table('declined_events')->where('declined_events.person_id','=',$user->id)
        ->join('places', 'places.id', '=', 'declined_events.place_id')
        ->join('types', 'types.id', '=', 'places.type')
        ->select('declined_events.*', 'types.image', 'places.lat', 'places.lng')
        ->get();

        return $declined_events->toJson();
    }

    function returnSubmitedEvents(){
        $user = Auth::user();
        $submited_events = DB::table('eventqueue')->where('eventqueue.person_id','=',$user->id)
        ->join('places', 'places.id', '=', 'eventqueue.place_id')
        ->join('types', 'types.id', '=', 'places.type')
        ->select('eventqueue.*', 'types.image', 'places.lat', 'places.lng')
        ->get();

        return $submited_events->toJson();
    }

    //===================================================================================
    //                      ALL DATA RETURN FOR USER PROFILE                  ***END***
    //===================================================================================


    //Event resubmition from user profile
    function resubmitEvent(Request $request){
        $declinedEvent = DeclinedEvents::findOrFail($request->id);

        if($request->time_from == $request->time_from){
            return false;
        }

        $validator = Validator::make($request->all(),[
            'place_id'=>'required',
            'title'=>'required|max:45',
            'about'=>'required|max:350',
            'time_from'=>'required',
            'time_until'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json("false", 200);
        }

        $exists = Event::where('place_id', $request->place_id)
            ->where(function ($query) use ($request) {
                $query->where([['time_from','<', $request->time_from],['time_until','>', $request->time_from],])
                    ->orWhere([['time_from','<', $request->time_until],['time_until','>', $request->time_until],])
                    ->orWhere([['time_from','>=', $request->time_from],['time_until','<=', $request->time_until],]);
            })
            ->exists();

        if(!$exists){
            $event =  new EventQueue;
            $event->place_id = $request->input('place_id');
            $event->title = $request->input('title');
            $event->about = $request->input('about');
            $event->time_from = $request->input('time_from');
            $event->time_until = $request->input('time_until');
            $event->person_id = Auth::id();

            if($event->save()){
                $declinedEvent->delete();
                return "true";
            }
            else{
                return "false";
            }
        }
        else{
            return "false";
        }

    }

    //Delete event from declined events in user profile
    function deleteEvent($id){

        $declinedEvent = DeclinedEvents::find($id);
        
        if($declinedEvent->delete()){
            return "true";
        }
        else{
            return "false";
        }
    }


    //Profile update    USERNAME|PHOTO|LIKED SPORTS
    function update_profile(Request $request){

        $user = Auth::user();

        $this->validate($request, [
            'username'=>'required|regex:/[a-zA-Z0-9\s]+/|max:20',
            'profile_image' => 'image|mimes:jpeg,png,jpg|nullable|max:1500000',
        ]);

        // Handle File Upload
        if($request->hasFile('profile_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('profile_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just text
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('profile_image')->storeAs('public/avatars', $fileNameToStore);

            if("blank-user-img.jpg" != $user->avatar){
                Storage::delete('public/avatars/'.$user->avatar);
            }

            $user->avatar = $fileNameToStore;
        } 

        $user->name = $request->input('username');
        
        if(!$request->input('types')){
            $user->liked_sports = json_encode([]);
        }
        else{
            $user->liked_sports = json_encode($request->input('types'));
        }

        $user->save();

        return redirect('/home');
    }

}
