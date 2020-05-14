<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Place;
use App\Payment;
use App\Events\WebsocketDemoEvent;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    // LOADING MAIN PAGE
    public function index(Request $request){

        $geoip = geoip()->getClientIP();

        if($geoip == '127.0.0.0'){
        $geoip = geoip()->getLocation('212.117.25.184');
        }else{
        $geoip = geoip()->getLocation($geoip);
        }
        
        $location = '{lat:'.$geoip->lat . ", lng:".$geoip->lon.'}';
        
        
        return view('index')->with(compact('location'));
    }

    //user page view for other users
    function show_profile($auth_id){

        $user = User::select('*')->where('auth_id', $auth_id)->get();

        return view('pages.user')->with(compact('user'));
    }

    //PAYMENT PAGE load up
    function payment_page($id){

        $user = Auth::user();

        $place = Place::find($id);

        return view('pages.place_payment')->with(compact('user', 'place'));
    }

    //Successful payment, storing all payment information
    function payment_successful(Request $request){

        $place = Place::findOrFail($request->place_id);
        $place->highlighted = 1;
        $place->highlight_valid = $request->highlight_valid;

        $payment = new Payment;
        $payment->person_id = $request->person_id;
        $payment->place_id = $request->place_id;
        $payment->status = $request->status;
        $payment->payer_name = $request->payer_name;
        $payment->payer_email = $request->payer_email;
        $payment->name = $request->name;
        $payment->amount = $request->amount;
        

        $payment->save();

        if($place->save()){
            return ["place was highlighted"];
        }
        else{
            return ["error"];
        }

    }


}
