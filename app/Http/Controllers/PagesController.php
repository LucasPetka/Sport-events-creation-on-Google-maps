<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Events\WebsocketDemoEvent;
use Torann\GeoIP\Facades\GeoIP;

class PagesController extends Controller
{
    public function index(Request $request){

        $geoip = geoip()->getClientIP();

        if($geoip == '127.0.0.0'){
        $geoip = geoip()->getLocation('86.30.223.189');
        }
        
        $location = '{lat:'.$geoip->lat . ", lng:".$geoip->lon.'}';
        
        
        return view('index')->with(compact('location'));
    }

    function show_profile($auth_id){

        $user = User::select('*')->where('auth_id', $auth_id)->get();

        return view('pages.user')->with(compact('user'));
    }
}
