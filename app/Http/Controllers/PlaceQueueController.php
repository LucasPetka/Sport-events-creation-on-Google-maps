<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaceQueue;
use App\Http\Resources\PlaceQueue as PlaceResource;

class PlaceQueueController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get places
        $places = PlaceQueue::all();

        //Return collection as a resource
        return PlaceResource::collection($places);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $place = $request->isMethod('put') ? PlaceQueue::findOrFail($request->place_id) : new PlaceQueue;

        $place->id = $request->input('place_id');
        $place->title = $request->input('title');
        $place->about = $request->input('about');
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');
        $place->type = $request->input('type');

        if($place->save()){
            return new PlaceResource($place);
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
        $place = PlaceQueue::findOrFail($id);

        return new PlaceResource($place);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $place = PlaceQueue::findOrFail($id);

        if($place->delete()){
        return new PlaceResource($place);
        }
    }
}
