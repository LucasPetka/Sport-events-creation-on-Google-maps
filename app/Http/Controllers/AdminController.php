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
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PlaceAccept;
use App\Notifications\PlaceDeclined;
use App\Notifications\EventAccept;
use App\Notifications\EventDeclined;
use App\Payment;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    { 
        $places = PlaceQueue::count();
        $events = EventQueue::count();
        $eventsOnMap = Event::count();
        $placesOnMap = Place::count();
        $registeredUsers = User::count();
        $confirmations = $places + $events;

        return view('admin')->with(compact('places','events','eventsOnMap','placesOnMap','registeredUsers', 'confirmations'));
    }

    public function places()
    { 
        $places = PlaceQueue::paginate(10);
        $places_count = PlaceQueue::count();
        $events = EventQueue::count();

        return view('admin.places')->with(compact('places', 'places_count', 'events'));
    }

    public function allPlaces()
    { 
        $places = Place::paginate(10);
        $places_count = PlaceQueue::count();
        $events = EventQueue::count();

        return view('admin.allPlaces')->with(compact('places', 'places_count', 'events'));
    }

    public function events()
    { 
        $places = PlaceQueue::count();
        $events_count = EventQueue::count();
        $events = EventQueue::paginate(10);
        
        return view('admin.events')->with(compact('places', 'events_count', 'events'));
    }

    public function sportTypes()
    { 
        $types = Type::all();
        $places = PlaceQueue::count();
        $events = EventQueue::count();


        return view('admin.sportTypes')->with(compact('types','places','events'));
    }


    public function users()
    { 
        $users = User::paginate(10);
        $places = PlaceQueue::count();
        $events = EventQueue::count();


        return view('admin.users')->with(compact('users', 'places','events'));
    }

    public function deleteUser($id)
    { 
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('admin/users');
    }

    public function deletePlace($id)
    { 
        $place = Place::findOrFail($id);

        if($place->delete()){
            return redirect('/admin/places')->with('error', 'Place has been deleted...');
        }
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



        
        if($accepted_place->save()){

            User::find($place->personid)->notify(new PlaceAccept($newPlace));
            $place->delete();
            return redirect('/admin/places_to_confirm')->with('success', 'Place has been added!');
        }
        else{
            return redirect('/admin/places_to_confirm')->with('error', 'Place has not been added...');
        }
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

        if($declinedPlace->save() && $place->delete()){

            User::find($declinedPlace->personid)->notify(new PlaceDeclined($declinedPlace));

            return redirect('/admin/places_to_confirm')->with('success', 'Place has been declined!');
        }
        else{
            return redirect('/admin/places_to_confirm')->with('error', 'Place has not been declined...');
        }

    }


    public function destroyPlace($id)
    {
        $place = PlaceQueue::findOrFail($id);

        if($place->delete()){
            return redirect('/admin/places_to_confirm')->with('error', 'Place has been deleted...');
        }
    }



    public function acceptEvent($id)
    { 
        $event = EventQueue::find($id);
        $start = $event->time_from;
        $end = $event->time_until;
        $place_id =$event->place_id;


        $exists = Event::where('place_id', $place_id)
            ->where(function ($query) use ($start, $end) {
                $query->where([['time_from','<', $start],['time_until','>', $start],])
                    ->orWhere([['time_from','<', $end],['time_until','>', $end],])
                    ->orWhere([['time_from','>=', $start],['time_until','<=', $end],]);
            })
            ->exists();


        if(Carbon::parse($start) < Carbon::now()){
            if(!$exists){
                $newEvent = new Event;
                $newEvent->place_id = $event->place_id;
                $newEvent->title = $event->title;
                $newEvent->about = $event->about;
                $newEvent->time_from = $event->time_from;
                $newEvent->time_until = $event->time_until;
                $newEvent->person_id = $event->person_id;

                if($newEvent->save() && $event->delete()){
                    User::find($newEvent->person_id)->notify(new EventAccept($newEvent));
                    return redirect('/admin/events_to_confirm')->with('success', 'Event has been accepted!');
                }
                else{
                    return redirect('/admin/events_to_confirm')->with('error', 'Event has not been accepted...');
                }
            }
            else{
                return redirect('/admin/events_to_confirm')->with('error', 'Event overlaping with other event');
            }
        }
        else{
            return redirect('/admin/events_to_confirm')->with('error', 'Cannot add event to past');
        }



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
        

        if($declinedEvent->save() && $event->delete()){
            
            User::find($declinedEvent->person_id)->notify(new EventDeclined($declinedEvent));

            return redirect('/admin/events_to_confirm')->with('success', 'Event has been declined!');
        }
        else{
            return redirect('/admin/events_to_confirm')->with('error', 'Event has not been declined...');
        }
    }

    public function destroyEvent($id)
    {
        $event = EventQueue::findOrFail($id);

        if($event->delete()){
            return redirect('/admin/events_to_confirm')->with('error', 'Event has been deleted...');
        }
    }


}
