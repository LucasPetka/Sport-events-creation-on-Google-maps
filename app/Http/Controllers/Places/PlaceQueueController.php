<?php

namespace App\Http\Controllers\Places;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlaceQueue;
use App\Place;
use App\Http\Resources\PlaceQueue as PlaceResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlaceQueueController extends Controller
{

    
    public function index()
    {
        //Get places
        $places = PlaceQueue::all();

        //Return collection as a resource
        return PlaceResource::collection($places);
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        if($user->isAdmin != '1'){
            $place = new PlaceQueue;
            $place->personid = Auth::id();
        }
        else{
            $place = new Place;
        }
        
        if($request->input('paid')) {
            $place->paid = 1;
        }
        else{
            $place->paid = 0;
        }

        $this->validate($request, [
            'title'=>'required|max:45',
            'about'=>'required|max:350',
            'lat'=>'required',
            'lng'=>'required',
            'type'=>'required|max:3'
        ]);

        $place->id = $request->input('place_id');
        $place->title = $request->input('title');
        $place->about = $request->input('about');
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');
        $place->type = $request->input('type');

        if($place->save()){
            return "true";
        }
        else{
            return "false";    
        }   

    }


    public function show($id)
    {
        $place = PlaceQueue::findOrFail($id);

        return new PlaceResource($place);
    }



    public function destroy($id)
    {

        $place = PlaceQueue::findOrFail($id);

        if($place->delete()){
        return new PlaceResource($place);
        }
    }


}
