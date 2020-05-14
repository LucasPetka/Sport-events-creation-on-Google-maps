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
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{

    //Returns list of a places to show on a map
    public function index($nelat, $swlat, $nelng, $swlng, Request $request)
    {

        $distance = $request->input('distance');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $paid = $request->input('paid'); //if paid or not

        //return by the block that you looking at using coordinates
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

    //return single place information by ID
    public function show($id)
    {
        $place = Place::findOrFail($id);

        return new PlaceResource($place);
    }

    //delete place by ID
    public function destroy($id)
    {
        $place = Place::findOrFail($id);

        $place->delete();
    }

    //update place data
    public function update(Request $request)
    {
        $place = Place::findOrFail($request->place_id);

        $this->validate($request, [
            'title'=>'required|max:45',
            'about'=>'required|max:350',
            'lat'=>'required',
            'lng'=>'required',
        ]);

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
