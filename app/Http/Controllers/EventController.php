<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Event;
use App\User;
use App\Http\Resources\Event as EventResource;

use Illuminate\Support\Facades\DB;
use App\PeopleGoing;
use App\Http\Resources\PeopleGoing as PeopleGoingResource;
use Auth;

use App\Message;
use App\Events\MessageSent;


class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->get();
        //Return collection as a resource
        return EventResource::collection($events);
    }

    public function get_events_by_place($id)
    {
        $events = Event::with('user')->where('place_id', $id)->get();
        
        return EventResource::collection($events);
    }


    public function store(Request $request)
    {
        $event = $request->isMethod('put') ? Event::findOrFail($request->id) : new Event;

        $event->id = $request->input('id');
        $event->place_id = $request->input('place_id');
        $event->title = $request->input('title');
        $event->about = $request->input('about');
        $event->time_from = $request->input('time_from');
        $event->time_until = $request->input('time_until');
        $event->person_id = Auth::id();

        if($event->save()){
            return new EventResource($event);
        }
    }


    public function show($id)
    {
        $event = Event::findOrFail($id);

        return new EventResource($event);
    }


    public function closestEvent($id)
    {
        $mytime = Carbon::now();

        $event = DB::table('events')
            ->select('*')
            ->where('place_id','=', $id)
            ->where('time_from','<', $mytime)
            ->where('time_until','>', $mytime)
            ->get();

        return EventResource::collection($event);
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        DB::table('people_going')->where('event_id', '=', $id)->delete();

        if($event->delete()){
        return new EventResource($event);
        }
    }


    public function show_event_page($id)
    {
        $event = Event::findOrFail($id);

        return view('pages.event')->with(compact('event')); 
    }


    public function fetchMessages($id)
    {
        return Message::with('user')->where('event_id', $id)->get();
    }


    public function sendMessage(Request $request)
    {
        $message = auth()->user()->messages()->create([
            'message' => $request->message,
            'event_id' => $request->event_id
        ]);

        broadcast(new MessageSent($message->load('user')))->toOthers();

        return ['status' => 'success'];
    }



}
