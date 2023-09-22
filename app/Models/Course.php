<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

   public function getImageAttribute($value)
   {
        return $this->attributes['image'] ? asset('public/images/course').'/'.$this->attributes['image'] : null;
   }

   public function course_content()
   {
     return $this->hasMany(CourseContent::class, 'course_id', 'id');
   }
}
