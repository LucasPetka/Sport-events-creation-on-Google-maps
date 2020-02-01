<?php

namespace App\Http\Controllers\Places;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Place;
use App\Http\Resources\Place as PlaceResource;
use DB;
use Auth;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($nelat, $swlat, $nelng, $swlng)
    {
        //Get places
        $places = DB::table('places')
            ->select('*')
            ->where('lat','<',$nelat)
            ->where('lat','>',$swlat)
            ->where('lng','<',$nelng)
            ->where('lng','>',$swlng)
            ->get();

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
        $place = $request->isMethod('put') ? Place::findOrFail($request->place_id) : new Place;

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
        $place = Place::findOrFail($id);

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

        $place = Place::findOrFail($id);

        if($place->delete()){
            return new PlaceResource($place);
        }
    }
}
