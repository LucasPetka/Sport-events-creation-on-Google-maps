<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PeopleGoing;
use App\Http\Resources\PeopleGoing as PeopleGoingResource;
use Auth;

class PeopleGoingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people_going = PeopleGoing::all();

        //Return collection as a resource
        return PeopleGoingResource::collection($people_going);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person = $request->isMethod('put') ? PeopleGoing::findOrFail($request->id) : new PeopleGoing;

        $person->id = $request->input('id');
        $person->place_id = $request->input('place_id');
        $person->event_id = $request->input('event_id');
        $person->person_id = Auth::id();

        if($person->save()){
            return new PeopleGoingResource($person);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = PeopleGoing::findOrFail($id);

        if($person->delete()){
        return new PeopleGoingResource($person);
        }
    }
}
