<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;


class AuthController extends Controller
{
    public function verifyCode(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11'
        ]);

        if($validation->fails()){
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }
        try {
            $sid = env("TWILIO_SID");
            $token = env("TWILIO_TOKEN");
            $client = new Client($sid, $token);
            $data['verify_code'] = random_int(100000, 999999);
            
            $client->messages->create(
                '+88'.$request->phone,
                [
                    'from' => env("TWILIO_NUMBER"),
                    'body' => "Your verification code is ".$data['verify_code']." Sent from ".env('APP_NAME')
                ]
            );

            $data['first_number'] = random_int(10, 99);
            $data['last_number'] = random_int(10, 99);
            $data['result'] = $data['first_number'] + $data['last_number'];

            return self::successWithResponse('Verification code sent successfully', 200, $data);

        } catch (Exception $e) {
            return self::errorWithResponse('something went wrong', 500);
        }
    }


    public function calculateNumber(Request $request)
    {
        try {
            $data['first_number'] = random_int(10, 99);
            $data['last_number'] = random_int(10, 99);
            $data['result'] = $data['first_number'] + $data['last_number'];
            return self::successWithResponse('Result sent successfully', 200, $data);
        } catch (Exception $e) {
            return self::errorWithResponse('something went wrong', 500);
        }
    }
    
    public function studentRegister(Request $request)
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
        $data['user_type'] = 'student';
        $data['login_id'] = mt_rand(00000000, 99999999);
        $student = new User($data);
        if($student->save()){
            return self::successWithResponse('Registration successfully', 201, $data);
        } else{
            return self::errorWithResponse('something went wrong', 500);
        }

    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        // try{
        //     $credentials = ['mobile' => $request->mobile, 'password' => $request->password];
        //     $user = User::where('mobile', $credentials['mobile'])->first();

        //     if (!$user || !Hash::check($credentials['password'], $user->password)) {
        //         return self::errorWithResponse('Credential does not match', 401);
        //     }
        //     $token  = $user->createToken('user api')->accessToken;

        //     return response()->json([
        //         'token' => $token,
        //         'user' => $user,
        //         'success' => true,
        //         'status_code' => 200,
        //     ], 200);

        // } catch(Exception $e){
        //     dd($e->getMessage());
        // }

        if (Auth::attempt(['password' => $request->password, 'mobile' => $request->mobile])) {
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
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return self::successWithResponse('User logout successfully', 205);

    }
    public function year()
    {
        $currentYear = date("Y");
        $years = array();
        for ($year = 1970; $year <= $currentYear; $year++) {
            $years[] = '{label: '.$year .', value: '.$year;
        }
        return self::successWithResponse('Year get successfully', 200, $years);
    }
 
    public function userInfo()
    {
        $data = Auth::user();
        return self::successWithResponse('Student info get successfully', 200, $data);
    }
 
}
