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
        $declinedPlace->title = $request->input('title');
        $declinedPlace->about = $request->input('about');
        $declinedPlace->lat = $request->input('lat');
        $declinedPlace->lng = $request->input('lng');


        $place =  new PlaceQueue;
        $place->title = $request->input('title');
        $place->about = $request->input('about');
        $place->about = $request->input('about');
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');
        $place->type = $declinedPlace->type;
        $place->personid = $declinedPlace->personid;

        if($place->save()){
            $declinedPlace->delete();
            return redirect('/home');
        }
    }

    public function destroy($id)
    {
        $declinedPlace = DeclinedPlaces::find($id);
        $declinedPlace->delete();

        return redirect('/home');
    }
}
