<?php

namespace App\Http\Controllers\Places;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DeclinedPlaces;
use App\PlaceQueue;
use Illuminate\Support\Facades\Validator;

class DeclinedPlacesController extends Controller
{

    public function update(Request $request, $id)
    {
        $declinedPlace = DeclinedPlaces::findOrFail($request->id);

        $validator = Validator::make($request->all(),[
            'title'=>'required|max:45',
            'about'=>'required|max:350',
            'lat'=>'required',
            'lng'=>'required',
            'type'=>'required|max:3'
        ]);

        if ($validator->fails()) {
            return response()->json( "false", 200);
        }

        $place =  new PlaceQueue;
        $place->title = $request->input('title');
        $place->about = $request->input('about');
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');
        $place->type = $request->input('type');
        if($request->input('paid')){
            $place->paid = 1;
        }
        else{
            $place->paid = 0;
        }
        
        $place->personid = $request->personid;

        if($place->save()){
            $declinedPlace->delete();
            return "true";
        }
        else{
            return "false";
        }

    }

    public function destroy($id)
    {
        $declinedPlace = DeclinedPlaces::find($id);
        

        if($declinedPlace->delete()){
            return "true";
        }
        else{
            return "false";
        }
        
    }
}
