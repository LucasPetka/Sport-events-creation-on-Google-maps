<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class PagesController extends Controller
{
    public function index(){
        return view('index');
    }

    public function myevents(Request $request){
        return Event::where('organizator', auth()->id())->get();

        if($request->ajax()){
            return Event::where('organizator', auth()->id())->get();
        }
        else{
            return view('home');
        }
    }
}
