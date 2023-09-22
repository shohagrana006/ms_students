<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function balanceTransfer(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required',
            'to' => 'required',
            'pin' => 'required'
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }
        $amount = intval($request->amount);

        $user = Auth::user();
        if($user->pin !== $request->pin){
            return self::errorWithResponse('Your pin is incorrect', 422);
            exit;
        }
        if($user->balance < $amount){
            return self::errorWithResponse('You have no sufficient balance',403);
            exit;
        }
        $user->balance -= $amount;
        $user->save();

        $to = User::where('login_id', $request->to)->first();
        $to->balance += $amount;
        $to->save();

        try {
            $transfer = new Transfer();
            $transfer->tansaction_type = 'Transfer balance';
            $transfer->tansaction_no = random_int(100000, 999999);
            $transfer->to_id = intval($to->id);
            $transfer->to = $to->email;
            $transfer->from_id = intval($user->id);
            $transfer->from = $user->email;
            $transfer->balance = $amount;
            $transfer->note = strval($request->note);
            if ($transfer->save()) {
                return self::successWithResponse('Balance transfer successfully', 200);
            }
        } catch (Exception $e) {
            return self::errorWithResponse('something went wrong', 500);
        }
    }


    public function transcation()
    {
        $user_id = Auth::user()->id;
        $data = Transfer::where('to_id', $user_id)->orWhere('from_id', $user_id)->get();
        if ($data) {
            return self::successWithResponse('Transcation get successfully', 200, $data);
        } else {
            return self::errorWithResponse('Transcation not found', 404);
        }
    }








}
