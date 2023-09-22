<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    public function changePassword(Request $request){
        $validation = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $user = Auth::user();
        if (!$user) {
            return self::errorWithResponse('user not found', 404);
        }
        if(Hash::check($request->current_password, $user->password)){
           $user->password = $request->new_password;
           if($user->save()){
                return self::successWithResponse('Password change successfully');
           }
        } else{
            return self::errorWithResponse('Password does not match', 400);
        }
    }
    public function changePin(Request $request){
        $validation = Validator::make($request->all(), [
            'current_pin' => 'required',
            'new_pin' => 'required|digits:4'
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $user = Auth::user();
        if (!$user) {
            return self::errorWithResponse('user not found', 404);
        }
        if($user->pin == $request->current_pin){
           $user->pin = $request->new_pin;
           if($user->save()){
                return self::successWithResponse('Pin change successfully');
           }
        } else{
            return self::errorWithResponse('Pin does not match', 400);
        }
    }
    public function setPin(Request $request){
        $validation = Validator::make($request->all(), [
            'pin' => 'required|numeric|digits:4',
            'confirm_pin' => 'required|same:pin',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $user = Auth::user();
        if (!$user) {
            return self::errorWithResponse('user not found', 404);
        }
        if($user->pin == null){
           $user->pin = $request->pin;
           if($user->save()){
                return self::successWithResponse('Pin set successfully');
           }
        } else{
            return self::errorWithResponse('Pin already exist');
        }
    }


    public function allUser(){
        return self::successWithResponse('All user get successfully',200, User::all());
    }
    public function makeAdmin(Request $request){
        $validation = Validator::make($request->all(), [
            'login_id' => 'required',
            'type' => 'required|in:1,2',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $user = User::where('login_id', $request->login_id)->first();
        if (!$user) {
            return self::errorWithResponse('user not found', 404);
        }

        $user->user_type = $request->type == 1 ? 'sub_admin' : 'user_admin';
        if($user->save()){
            return self::successWithResponse('Admin create successfully');
        }

    }





}
