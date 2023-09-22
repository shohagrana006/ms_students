<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function getDebitAttribute($value){
        if ($this->from_id == auth()->id()) {
            return $this->balance;
        }
    }
    public function getCreditAttribute($value){
        if ($this->to_id == auth()->id()) {
            return $this->balance;
        }
    }

    protected $hidden = ['to_id', 'from_id'];

}
