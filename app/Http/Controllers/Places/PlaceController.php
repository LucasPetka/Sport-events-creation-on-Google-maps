<?php

namespace App\Http\Controllers\Places;

use App\Events\WebsocketDemoEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Place;
use App\Http\Resources\Place as PlaceResource;
use DB;
use Auth;
use App\Message;
use App\Events\MessageSent;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($nelat, $swlat, $nelng, $swlng, Request $request)
    {

        $distance = $request->input('distance');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $paid = $request->input('paid');

        $places = DB::table('places')->select('*')->where('lat','<',$nelat)->where('lat','>',$swlat)->where('lng','<',$nelng)->where('lng','>',$swlng);

        if(null !== $request->input('paid') && $request->input('paid') != "Any"){
            if(null !== $request->input('distance') && $request->input('type') != "All" && null !== $request->input('type') && $request->input('distance') != "Any"){ 
                $places = $places->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
                ->where('type','=',$request->input('type'))
                ->where('paid','=',$request->input('paid'))
                ->get();
            }
            else if(null !== $request->input('distance') && $request->input('distance') != "Any"){
                $places = $places->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
                ->where('paid','=',$request->input('paid'))
                ->get();
            }
            else if(null !== $request->input('type') && $request->input('type') != "All"){
                $places = $places->where('type','=',$request->input('type'))
                ->where('paid','=',$request->input('paid'))
                ->get();
            }
            else{
                //Get places
                $places = $places->where('paid','=',$request->input('paid'))->get();
            }
        }
        else{
            if(null !== $request->input('distance') && $request->input('type') != "All" && null !== $request->input('type') && $request->input('distance') != "Any"){ 
                $places = $places->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
                ->where('type','=',$request->input('type'))
                ->get();
            }
            else if(null !== $request->input('distance') && $request->input('distance') != "Any"){
                $places = $places->whereRaw('? > ( 6371 * acos ( cos ( radians( ? ) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( ? ) ) + sin ( radians( ? ) ) * sin( radians( lat ) ) ) )', [$distance, $lat, $lng, $lat])
                ->get();
            }
            else if(null !== $request->input('type') && $request->input('type') != "All"){
                $places = $places->where('type','=',$request->input('type'))
                ->get();
            }
            else{
                //Get places
                $places = $places->get();
            }
        }

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


    public function update(Request $request)
    {
        $place = Place::findOrFail($request->place_id);

        $place->id = $request->input('place_id');
        $place->title = $request->input('title');
        $place->about = $request->input('about');
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');
        $place->paid = $request->input('paid');

        if($place->update()){
            return redirect('/admin/places')->with('success', 'Place has been updated!');
        }


    }


}
