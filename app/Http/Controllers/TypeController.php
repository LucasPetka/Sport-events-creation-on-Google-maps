<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Type;
use App\Http\Resources\Types as TypesResource;

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


    public function show($id)
    {
        $type = Type::findOrFail($id);

        return new TypesResource($type);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
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
