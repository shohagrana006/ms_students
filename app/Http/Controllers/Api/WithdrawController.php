<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class WithdrawController extends Controller
{
    public function withdrawBalance(Request $request){
        $validation = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_type' => 'required|in:cash,bank'
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }
        $amount = intval($request->amount);

        $user = Auth::user();
        if($user->balance == null || $user->balance < $amount){
            return self::errorWithResponse('You have no sufficient balance',403);
        }
        try {
            $withdraw = new Withdraw();
            $withdraw->user_id = $user->id;
            $withdraw->payment_type = $request->payment_type;
            $withdraw->amount = $amount;
            $withdraw->tansaction_no = random_int(100000, 999999);
            $withdraw->tansaction_type = 'Withdraw Request';
            $withdraw->debit = $amount;
            $withdraw->status = 'pending';
            if($withdraw->save()){
                $sid = env("TWILIO_SID");
                $token = env("TWILIO_TOKEN");
                $client = new Client($sid, $token);
                $data['verify_code'] = random_int(100000, 999999);

                
                $client->messages->create(
                    '+88' . $user->mobile,
                    [
                        'from' => env("TWILIO_NUMBER"),
                        'body' => "Your verification code is " . $data['verify_code'] . " Sent from " . env('APP_NAME')
                        ]
                    );

                $superAdmin = User::where('user_type', 'super_admin')->where('email', env('SUPER_ADMIN_EMAIL'))->first();
                    
                $client->messages->create(
                    '+88' . $superAdmin->mobile,
                    [
                        'from' => env("TWILIO_NUMBER"),
                        'body' => "User verification code is " . $data['verify_code'] . " and amount is ".$amount." Sent from " . env('APP_NAME')
                    ]
                );

                return self::successWithResponse('Verification code sent successfully', 200);
            }
        } catch (Exception $e) {
            return self::errorWithResponse('something went wrong', 500);
        }
    }

    public function commisionLedger(){
        $user_id = Auth::user()->id;
        $data = Withdraw::where('user_id', $user_id)->get();
        if($data){
            return self::successWithResponse('Commision ledger get successfully', 200, $data);
        } else{
            return self::errorWithResponse('Commision ledger not found', 404);
        }
    }








}
