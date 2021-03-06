<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PeopleGoing;
use App\Http\Resources\PeopleGoing as PeopleGoingResource;
use Auth;

class PeopleGoingController extends Controller
{
    //----------------------Return all DB people going-----------------------------
    public function index()
    {
        $people_going = PeopleGoing::all();

        //Return collection as a resource
        return PeopleGoingResource::collection($people_going);
    }

    //-------------------Adds person to going list DB-----------------------------
    public function store(Request $request)
    {
        $person = new PeopleGoing;

        $exists = PeopleGoing::where('place_id', $request->input('place_id'))
            ->where('event_id', $request->input('event_id'))
            ->where('person_id', Auth::id())
            ->exists();

        if(!$exists){
            $person->place_id = $request->input('place_id');
            $person->event_id = $request->input('event_id');
            $person->person_id = Auth::id();

            if($person->save()){
                return new PeopleGoingResource($person);
            }
        }
        else{
            return "false";
        }
    }

    //---------------------Return list of people who are going to specific event----------------------
    public function returnByEvent($id)
    {
        $people_going = PeopleGoing::where('event_id', $id)->get();

        //Return collection as a resource
        return PeopleGoingResource::collection($people_going);
    }

    //---------------------Leave the event / Deletes person from going to event--------------------------
    public function destroy($id)
    {
        $person = PeopleGoing::findOrFail($id);

        if($person->delete()){
        return new PeopleGoingResource($person);
        }
    }
}
