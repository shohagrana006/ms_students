<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRefer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function student(){
        return $this->belongsTo(User::class, 'student_login_id', 'login_id');
    }

    public function ref(){
        return $this->belongsTo(User::class, 'ref_login_id', 'login_id');
    }

    public function placement(){
        return $this->belongsTo(User::class, 'placement_login_id', 'login_id');
    }

}
