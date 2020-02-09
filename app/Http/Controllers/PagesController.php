<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Events\WebsocketDemoEvent;

class PagesController extends Controller
{
    public function index(){
        
        return view('index');
    }
}
