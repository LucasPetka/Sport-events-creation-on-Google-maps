<?php

namespace App\Http\Controllers\Events;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;

//---USER
use App\User;

//---EVENTS
use App\Event;
use App\Http\Resources\Event as EventResource;
use App\EventQueue;
use App\DeclinedEvents;

//---PEOPLE_GOING
use App\PeopleGoing;
use App\Http\Resources\PeopleGoing as PeopleGoingResource;

//--MESSAGING
use App\Message;
use App\Events\MessageSent;


class EventController extends Controller
{

    //---Return all events
    public function index()
    {
        $events = Event::with('user')->get();
        return EventResource::collection($events);
    }

    //---Return all events in specific place
    public function get_events_by_place($id)
    {
        $events = Event::with('user')->where('place_id', $id)->orderBy('time_from', 'asc')->get();
        return EventResource::collection($events);
    }


    public function store(Request $request)
    {
        $event = $request->isMethod('put') ? Event::findOrFail($request->id) : new EventQueue;

        //========================Validation==========START==========

        $start = $request->input('time_from');
        $end =  $request->input('time_until');
        $place_id = $request->input('place_id');

        $validator = Validator::make($request->all(),[
            'place_id'=>'required',
            'title'=>'required|max:45',
            'about'=>'required|max:350',
            'time_from'=>'required',
            'time_until'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There was incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 200);
        }

        if(isset($event->id)){
            $exists = Event::where('place_id', $place_id)
            ->where('id', '!=', $event->id)
            ->where(function ($query) use ($start, $end) {
                $query->where([['time_from','<', $start],['time_until','>', $start],])
                    ->orWhere([['time_from','<', $end],['time_until','>', $end],])
                    ->orWhere([['time_from','>=', $start],['time_until','<=', $end],]);
            })
            ->exists();
        }
        else{
            $exists = Event::where('place_id', $place_id)
            ->where(function ($query) use ($start, $end) {
                $query->where([['time_from','<', $start],['time_until','>', $start],])
                    ->orWhere([['time_from','<', $end],['time_until','>', $end],])
                    ->orWhere([['time_from','>=', $start],['time_until','<=', $end],]);
            })
            ->exists();
        }

        //========================Validation==========END==========

        if(!$exists){
            $event->id = $request->input('id');
            $event->place_id = $request->input('place_id');
            $event->title = $request->input('title');
            $event->about = $request->input('about');
            $event->time_from = $request->input('time_from');
            $event->time_until = $request->input('time_until');
            $event->person_id = Auth::id();

            if($event->save()){
                return "true";
            }
        }
        else{
            return "false";
        }


    }


    public function show($id)
    {
        $event = Event::findOrFail($id);

        return new EventResource($event);
    }

    public function getDeclinedEvent($id)
    {
        $declinedEvent = DeclinedEvents::findOrFail($id);

        return new EventResource($declinedEvent);
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
            return "true";
        }
        else{
            return "false";
        }
    }

    //--------------Check if event time is not overlaping with others----------
    public function check_time(Request $request) {
        
        $start = $request->start;
        $end = $request->end;
        $place_id = $request->place_id;
        $event_id = $request->event_id;

        $exists = Event::where('place_id', $place_id)
        ->where('id', '!=', $event_id)
        ->where(function ($query) use ($start, $end) {
            $query->where([['time_from','<', $start],['time_until','>', $start],])
                ->orWhere([['time_from','<', $end],['time_until','>', $end],])
                ->orWhere([['time_from','>=', $start],['time_until','<=', $end],]);
        })
        ->exists();

        

        if ($exists) {
            return response()->json(array('found'=> true), 200);
        }
        else{
            return response()->json(array('found'=> false), 200);
        }
     }



    //===================================================================================================
    //----------------------------------Event page and messaging controllers-----------------------------
    //===================================================================================================

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
