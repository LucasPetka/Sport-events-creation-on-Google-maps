<?php

namespace App\Http\Controllers\Places;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlaceQueue;
use App\Http\Resources\PlaceQueue as PlaceResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlaceQueueController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get places
        $places = PlaceQueue::all();

        //Return collection as a resource
        return PlaceResource::collection($places);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $place = $request->isMethod('put') ? PlaceQueue::findOrFail($request->place_id) : new PlaceQueue;

        if($request->input('paid')) {
            $place->paid = 1;
        }
        else{
            $place->paid = 0;
        }

        $validator = Validator::make($request->all(),[
            'title'=>'required|max:45',
            'about'=>'required|max:350',
            'lat'=>'required',
            'lng'=>'required',
            'type'=>'required|max:3'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There was incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 200);
        }

        $place->id = $request->input('place_id');
        $place->title = $request->input('title');
        $place->about = $request->input('about');
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');
        $place->type = $request->input('type');
        $place->personid = Auth::id();

        if($place->save()){
            return "true";
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = PlaceQueue::findOrFail($id);

        return new PlaceResource($place);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $place = PlaceQueue::findOrFail($id);

        if($place->delete()){
        return new PlaceResource($place);
        }
    }


}
