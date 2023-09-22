<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'course_id' => 'required|exists:courses,id',
            'content_no' => 'required',
            'content' => 'required',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $course_content = new CourseContent();
        $course_content->course_id = $request->course_id;
        $course_content->content_no = $request->content_no;
        $course_content->content = $request->content;
        if($course_content->save()){
            return self::successWithResponse('Course content create successfully', 201);
        } else {
            return self::errorWithResponse('something went wrong', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
