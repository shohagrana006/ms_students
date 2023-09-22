<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminAuthController extends Controller
{
    public function adminRegister(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11|unique:users,mobile',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'address' => 'required',
            'father_name' => 'required',
            'father_number' => 'required|numeric|digits:11',
            'guardian_name' => 'required',
            'guardian_number' => 'required|numeric|digits:11',
            'district' => 'required',
            'country' => 'required',
            'date_of_birth' => 'required',
            'contact_person' => 'required',
            'nid_no' => 'required|numeric',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);


        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }


        $data = $validation->validated();
        unset($data['confirm_password']);
        $data['user_type'] = 'super_admin';
        $data['login_id'] = mt_rand(00000000, 99999999);
        $admin = new User($data);
        if ($admin->save()) {
            return self::successWithResponse('Registration successfully', 201, $data);
        } else {
            return self::errorWithResponse('something went wrong', 500);
        }
    }
    public function subAdminRegister(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11|unique:users,mobile',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'address' => 'required',
            'father_name' => 'required',
            'father_number' => 'required|numeric|digits:11',
            'guardian_name' => 'required',
            'guardian_number' => 'required|numeric|digits:11',
            'district' => 'required',
            'country' => 'required',
            'date_of_birth' => 'required',
            'contact_person' => 'required',
            'nid_no' => 'required|numeric',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);


        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $data = $validation->validated();
        unset($data['confirm_password']);
        $data['user_type'] = 'sub_admin';
        $data['login_id'] = mt_rand(00000000, 99999999);
        $admin = new User($data);
        if ($admin->save()) {
            return self::successWithResponse('Registration successfully', 201, $data);
        } else {
            return self::errorWithResponse('something went wrong', 500);
        }
    }
    public function userAdminRegister(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11|unique:users,mobile',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'address' => 'required',
            'father_name' => 'required',
            'father_number' => 'required|numeric|digits:11',
            'guardian_name' => 'required',
            'guardian_number' => 'required|numeric|digits:11',
            'district' => 'required',
            'country' => 'required',
            'date_of_birth' => 'required',
            'contact_person' => 'required',
            'nid_no' => 'required|numeric',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);


        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $data = $validation->validated();
        unset($data['confirm_password']);
        $data['user_type'] = 'user_admin';
        $data['login_id'] = mt_rand(00000000, 99999999);
        $admin = new User($data);
        if ($admin->save()) {
            return self::successWithResponse('Registration successfully', 201, $data);
        } else {
            return self::errorWithResponse('something went wrong', 500);
        }
    }
    public function sellerRegister(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11|unique:users,mobile',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'address' => 'required',
            'father_name' => 'required',
            'father_number' => 'required|numeric|digits:11',
            'guardian_name' => 'required',
            'guardian_number' => 'required|numeric|digits:11',
            'district' => 'required',
            'country' => 'required',
            'date_of_birth' => 'required',
            'contact_person' => 'required',
            'nid_no' => 'required|numeric',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);


        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $data = $validation->validated();
        unset($data['confirm_password']);
        $data['user_type'] = 'seller';
        $data['login_id'] = mt_rand(00000000, 99999999);
        $admin = new User($data);
        if ($admin->save()) {
            return self::successWithResponse('Registration successfully', 201, $data);
        } else {
            return self::errorWithResponse('something went wrong', 500);
        }
    }

    /** @var \App\Models\User $user **/
    public function adminLogin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'login_id' => 'required',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        if (Auth::attempt(['password' => $request->password, 'login_id' => $request->login_id])) {
            $user = Auth::user();
            $data['user'] = $user;
            $data['token'] = $user->createToken('user api')->accessToken;
            
            if ($user) {
                return self::successWithResponse('Login successfully', 200, $data);
            } else {
                return self::errorWithResponse('Not found', 404);
            }
            
        } else {
            return self::errorWithResponse('Invalid credential', 403);
        }
    }

    public function adminLogout(Request $request)
    {
        $request->user()->token()->revoke();
        return self::successWithResponse('Admin logout successfully', 205);
    }

    public function adminInfo()
    {
        $data = Auth::user();
        return self::successWithResponse('Admin info get successfully', 200, $data);
    }




}
