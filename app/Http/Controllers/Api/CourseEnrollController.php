<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseEnrollController extends Controller
{
    public function courseEnroll($id)
    {
        $validation = Validator::make(['course_id' => $id], [
            'course_id' => 'exists:courses,id'
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $course_enroll = new CourseEnroll();
        $course_enroll->student_id = auth()->id();
        $course_enroll->course_id = $id;
        if($course_enroll->save()){
            return self::successWithResponse('Successfully Enroll', 201);
        } else{
            return self::errorWithResponse('Something went wrong', 404);
        }

    }
    public function studentCourse()
    {
        $sutdent_id = auth()->id();

        $course_enroll = CourseEnroll::where('student_id', $sutdent_id)->pluck('course_id')->toArray();
        $course = Course::whereIn('id',$course_enroll)->get();

        $course_enroll = new CourseEnroll();
        $course_enroll->student_id = auth()->id();
        $course_enroll->course_id = $id;
        if($course_enroll->save()){
            return self::successWithResponse('Successfully Enroll', 201);
        } else{
            return self::errorWithResponse('Something went wrong', 404);
        }

    }







}
