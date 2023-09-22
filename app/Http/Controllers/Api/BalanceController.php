<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
    public function BalanceSend(Request $request){
        $validation = Validator::make($request->all(), [
            'amount' => 'required',
            'sub_admin_id' => 'required',
        ]);
        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }
        $amount = intval($request->amount);
        $id = intval($request->sub_admin_id);

        $user = User::findOrFail($id);
        if($user->user_type == 'sub_admin'){
            $user->balance += $amount;
            if ($user->save()) {
                return self::successWithResponse('Ammount send successfully');
            } else {
                return self::errorWithResponse('Something went wrong', 400);
            }
        } else {
            return self::errorWithResponse('Something went wrong', 400);
        }
    }


    public function userBalance(){
        $data = Auth::user();
        if ($data) {
            return self::successWithResponse('User balance get successfully',200, ['balance' => $data->balance]);
        } else {
            return self::errorWithResponse('Something went wrong', 400);
        }
    }
    public function subAdminList(){
        $data = User::where('user_type', 'sub_admin')->get();
        if ($data) {
            return self::successWithResponse('Sub admin get successfully',200, $data);
        } else {
            return self::errorWithResponse('Something went wrong', 400);
        }
    }
    public function withdrawDone(Request $request){
        $validation = Validator::make($request->all(), [
            'withdraw_id' => 'required|exists:withdraws,id'
        ]);
        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }
        $data = Withdraw::where('id', $request->withdraw_id)->where('status', 'pending')->first();
        if ($data) {
            $user = User::where('id', $data->user_id)->first();
            $user->balance -= $data->debit;
            if($user->save()){
                $data->status = 'paid';
                $data->save();
                return self::successWithResponse('Successfully paid',200);
            }
        } else {
            return self::errorWithResponse('This Request already paid');
        }
    }







}
