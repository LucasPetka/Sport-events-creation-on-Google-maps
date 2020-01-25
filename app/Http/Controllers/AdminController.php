<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlaceQueue;
use App\Place;
use App\Type;
use App\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    { 
        $places = PlaceQueue::all();
        $types = Type::all();
        $users = User::all();

        return view('admin')->with(compact('places', 'types', 'users'));
    }

    public function sportTypes()
    { 
        $types = Type::all();
        $places = PlaceQueue::all();

        return view('admin.sportTypes')->with(compact('types','places'));
    }


    public function users()
    { 
        $users = User::all();
        $places = PlaceQueue::all();

        return view('admin.users')->with(compact('users', 'places'));
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
        $newPlace->created_at = $place->created_at;
        $newPlace->updated_at = $place->updated_at;
        $newPlace->save();

        $place->delete();

        return redirect('/admin');
    }

    public function declinePlace($id)
    { 
        $place = PlaceQueue::find($id);
        $place->delete();

        return redirect('/admin');
    }

    public function storeSportType(Request $request)
    {

        $this->validate($request, [
            'sport_id' => 'required',
            'sport_name' => 'required',
            'sport_logo' => 'required|image|nullable|max:15000'
        ]);

        // Handle File Upload
        if($request->hasFile('sport_logo')){
            // Get filename with the extension
            $filenameWithExt = $request->file('sport_logo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just text
            $extension = $request->file('sport_logo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('sport_logo')->storeAs('public/sport_logo', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $type = new Type;
        $type->id = $request->input('sport_id');
        $type->name = $request->input('sport_name');
        $type->image = $request->input('sport_logo');
        $type->image = $fileNameToStore;
        $type->timestamps = false;
        $type->save();

        return redirect('/admin/sporttypes')->with('success', 'Sport Type has been succesfuly added');


    }

    public function deleteSportType($id)
    {
        $type = Type::find($id);
        
        //Check if type exists before deleting
        if (!isset($type)){
            return redirect('/posts')->with('error', 'No Type Found');
        }
        if($type->image != 'noimage.jpg'){
        // Delete Image
            Storage::delete('public/sport_logo/'.$type->image);
        }
        
        $type->delete();
        return redirect('/admin/sporttypes')->with('success', 'Sport type deleted');
    }

}
