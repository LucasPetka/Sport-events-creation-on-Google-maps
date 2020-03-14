<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeclinedPlaces;
use App\PlaceQueue;

class DeclinedPlacesController extends Controller
{

    public function update(Request $request, $id)
    {
        $declinedPlace = DeclinedPlaces::findOrFail($request->id);

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
            return ['Success'];
        }
    }

    public function destroy($id)
    {
        $declinedPlace = DeclinedPlaces::find($id);
        $declinedPlace->delete();

        return redirect('/home');
    }
}
