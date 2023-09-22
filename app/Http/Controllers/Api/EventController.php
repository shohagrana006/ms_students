<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = Event::latest()->get();
        return self::successWithResponse('Event get successfully', 200, $course);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $event = new Event();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/event'), $imageName);
            $event->image = $imageName;
        }
        $event->title = $request->title;
        $event->description = $request->description;
        $event->vanue = $request->vanue;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->date = $request->date;
        

        if ($event->save()) {
            return self::successWithResponse('Event create successfully', 201);
        } else {
            return self::errorWithResponse('something went wrong', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::where('id', $id)->first();
        if ($event) {
            return self::successWithResponse('Course successfully get', 200, $event);
        } else {
            return self::errorWithResponse('Data not found', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
