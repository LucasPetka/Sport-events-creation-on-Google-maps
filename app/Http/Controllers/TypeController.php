<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Type;
use App\Http\Resources\Types as TypesResource;
use Illuminate\Support\Facades\Storage;
use App\PlaceQueue;
use App\EventQueue;

class TypeController extends Controller
{

    public function index()
    {
        //Get places
        $types = Type::all();

        //Return collection as a resource
        return TypesResource::collection($types);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sport_name' => 'required',
            'sport_logo' => 'required|image|nullable|max:15000',
            'sport_logo_highlighted' => 'required|image|nullable|max:15000'
        ]);

        // Handle File Upload
        if($request->hasFile('sport_logo') && $request->hasFile('sport_logo_highlighted')){
            // Get filename with the extension
            $filenameWithExt = $request->file('sport_logo')->getClientOriginalName();
            $filenameWithExt2 = $request->file('sport_logo_highlighted')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            // Get just text
            $extension = $request->file('sport_logo')->getClientOriginalExtension();
            $extension2 = $request->file('sport_logo_highlighted')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $fileNameToStore2= $filename2.'_'.time().'.'.$extension2;
            // Upload Image
            $path = $request->file('sport_logo')->storeAs('public/sport_logo', $fileNameToStore);
            $path2 = $request->file('sport_logo_highlighted')->storeAs('public/sport_logo', $fileNameToStore2);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $type = new Type;
        $type->id = $request->input('sport_id');
        $type->name = $request->input('sport_name');
        $type->image = $fileNameToStore;
        $type->image_h = $fileNameToStore2;
        $type->timestamps = false;
        $type->save();

        return redirect('/admin/sporttypes')->with('success', 'Sport Type has been succesfuly added');
    }


    public function show($id)
    {
        $type = Type::findOrFail($id);

        return new TypesResource($type);
    }

    public function openUpdate($id){

        $type = Type::findOrFail($id);
        $places = PlaceQueue::count();
        $events = EventQueue::count();

        return view('admin.editsporttype')->with(compact('type','places','events'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'sport_name' => 'required',
            'sport_logo' => 'required|image|nullable|max:15000',
            'sport_logo_highlighted' => 'required|image|nullable|max:15000'
        ]);

        $type = Type::find($id);
        Storage::delete('public/sport_logo/'.$type->image);
        Storage::delete('public/sport_logo/'.$type->image_h);

        // Handle File Upload
        if($request->hasFile('sport_logo') && $request->hasFile('sport_logo_highlighted')){
            // Get filename with the extension
            $filenameWithExt = $request->file('sport_logo')->getClientOriginalName();
            $filenameWithExt2 = $request->file('sport_logo_highlighted')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            // Get just text
            $extension = $request->file('sport_logo')->getClientOriginalExtension();
            $extension2 = $request->file('sport_logo_highlighted')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $fileNameToStore2= $filename2.'_'.time().'.'.$extension2;
            // Upload Image
            $path = $request->file('sport_logo')->storeAs('public/sport_logo', $fileNameToStore);
            $path2 = $request->file('sport_logo_highlighted')->storeAs('public/sport_logo', $fileNameToStore2);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $type->name = $request->input('sport_name');
        $type->image = $fileNameToStore;
        $type->image_h = $fileNameToStore2;
        $type->timestamps = false;
        $type->update();

        return redirect('/admin/sporttypes')->with('success', 'Sport Type has been succesfuly updated');
    }

    public function destroy($id)
    {
        $type = Type::find($id);
        
        //Check if type exists before deleting
        if (!isset($type)){
            return redirect('/posts')->with('error', 'No Type Found');
        }
        if($type->delete()){
        // Delete Image
            Storage::delete('public/sport_logo/'.$type->image);
        }
        
        $type->delete();
        return redirect('/admin/sporttypes')->with('success', 'Sport type deleted');
    }
}
