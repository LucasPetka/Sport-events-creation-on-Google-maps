<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Events\WebsocketDemoEvent;

class PagesController extends Controller
{
    public function index(){
        
        return view('index');
    }

    function show_profile($auth_id){

        $user = User::select('*')->where('auth_id', $auth_id)->get();

        return view('pages.user')->with(compact('user'));
    }
}
