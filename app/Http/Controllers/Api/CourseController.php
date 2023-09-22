<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = Course::latest()->get();
        return self::successWithResponse('course get successfully', 200, $course);
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

        $course = new Course();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/course'), $imageName);
            $course->image = $imageName;
        }
        $course->title = $request->title;
        $course->description = $request->description;
        $course->price = $request->price;
        $course->discount = $request->discount;
        $course->rating = $request->rating;
        $course->best_seller = $request->best_seller;
        $course->top_course = $request->top_course;
        $course->student_view = $request->student_view;
        $course->enroll = $request->enroll;
        $course->comment = $request->comment;
        $course->lecture = $request->lecture;
        $course->quizzes = $request->quizzes;
        $course->skill_level = $request->skill_level;
        $course->assessment = $request->assessment;

       if($course->save()){
           return self::successWithResponse('Course create successfully', 201);
       }else{
           return self::errorWithResponse('something went wrong', 500);
       }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::with('course_content')->where('id', $id)->first();
        if($course){
            return self::successWithResponse('Course successfully get', 200, $course);
        } else{
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
