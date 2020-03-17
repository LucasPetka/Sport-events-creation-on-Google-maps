<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaceQueue;
use App\EventQueue;
use App\Place;
use App\Event;
use App\AcceptedPlaces;
use App\DeclinedPlaces;
use App\DeclinedEvents;
use App\Type;
use App\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    { 
        $places = PlaceQueue::all();
        $events = EventQueue::all();


        return view('admin')->with(compact('places','events'));
    }

    public function places()
    { 
        $places = PlaceQueue::all();
        $events = EventQueue::all();

        return view('admin.places')->with(compact('places','events'));
    }

    public function events()
    { 
        $places = PlaceQueue::all();
        $events = EventQueue::all();
        
        return view('admin.events')->with(compact('places','events'));
    }

    public function sportTypes()
    { 
        $types = Type::all();
        $places = PlaceQueue::all();
        $events = EventQueue::all();


        return view('admin.sportTypes')->with(compact('types','places','events'));
    }


    public function users()
    { 
        $users = User::all();
        $places = PlaceQueue::all();
        $events = EventQueue::all();


        return view('admin.users')->with(compact('users', 'places','events'));
    }

    public function deleteUser($id)
    { 
        $user = User::find($id);
        $user->delete();

        return redirect('admin/users');
    }
    

    public function acceptPlace($id)
    { 
        $place = PlaceQueue::find($id);

        $newPlace = new Place;
        $newPlace->title = $place->title;
        $newPlace->about = $place->about;
        $newPlace->lat = $place->lat;
        $newPlace->lng = $place->lng;
        $newPlace->type = $place->type;
        $newPlace->paid = $place->paid;
        $newPlace->created_at = $place->created_at;
        $newPlace->updated_at = $place->updated_at;
        $newPlace->save();

        $accepted_place = new AcceptedPlaces;
        $accepted_place->person_id = $place->personid;
        $accepted_place->place_id = $newPlace->id;
        $accepted_place->save();


        $place->delete();

        return redirect('/admin/places');
    }

    public function declinePlace($id)
    { 
        $place = PlaceQueue::find($id);
        
        $declinedPlace = new DeclinedPlaces;
        $declinedPlace->title = $place->title;
        $declinedPlace->about = $place->about;
        $declinedPlace->lat = $place->lat;
        $declinedPlace->lng = $place->lng;
        $declinedPlace->type = $place->type;
        $declinedPlace->paid = $place->paid;
        $declinedPlace->personid = $place->personid;
        $declinedPlace->save();
        
        
        $place->delete();

        return redirect('/admin/places');
    }


    public function acceptEvent($id)
    { 
        $event = EventQueue::find($id);

        $newEvent = new Event;
        $newEvent->place_id = $event->place_id;
        $newEvent->title = $event->title;
        $newEvent->about = $event->about;
        $newEvent->time_from = $event->time_from;
        $newEvent->time_until = $event->time_until;
        $newEvent->person_id = $event->person_id;
        $newEvent->save();

        $event->delete();

        return redirect('/admin/events');
    }

    public function declineEvent($id)
    { 
        $event = EventQueue::find($id);
        
        $declinedEvent = new DeclinedEvents;
        $declinedEvent->place_id = $event->place_id;
        $declinedEvent->title = $event->title;
        $declinedEvent->about = $event->about;
        $declinedEvent->time_from = $event->time_from;
        $declinedEvent->time_until = $event->time_until;
        $declinedEvent->person_id = $event->person_id;
        $declinedEvent->save();
        
        $event->delete();

        return redirect('/admin/events');
    }


}
