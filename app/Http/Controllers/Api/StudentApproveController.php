<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentRefer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentApproveController extends Controller
{
    public function index(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'student_login_id' => [
                                'required',
                                'exists:users,login_id',
                                function ($attribute, $value, $fail) use ($request) {
                                    if ($value == $request->input('ref_login_id') || $value == $request->input('placement_login_id') || $value == $request->input('net_office')) {
                                        $fail('The student ID must not be the same as the ref ID or referral ID.');
                                    }
                                },
                            ],
            'ref_login_id'       => 'required|exists:users,login_id',

            'placement_login_id' =>  [
                                    'required',
                                    'exists:users,login_id',
                                    function ($attribute, $value, $fail) use ($request) {
                                        
                                        // if ($value == $request->input('student_id') || $value == $request->input('net_office')) {
                                        //     $fail('The student ID must not be the same as the ref ID or referral ID.');
                                        // }
                                        if ($value == $request->input('ref_login_id') || $value == $request->input('student_id') || $value == $request->input('net_office')) {
                                            $fail('The student ID must not be the same as the ref ID or referral ID.');
                                        }
                                    },
                                
                                ],

            'position'    => 'required|in:1,2',

            'net_office'  =>    [
                                    'required',
                                    'exists:users,login_id',
                                    function ($attribute, $value, $fail) use ($request) {
                                        $refId = $request->input('ref_login_id');
                                        
                                        if ($value == $request->input('ref_login_id') || $value == $request->input('student_id') || $value == $request->input('placement_login_id')) {
                                            $fail('The student ID must not be the same as the ref ID or referral ID.');
                                        }
                                    },
                                ],
        ]);

        if($validation->fails()){
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }



        $student_ref = StudentRefer::where('ref_login_id', $request->input('ref_login_id'))->where('position', $request->input('position'))->first();

        if ($student_ref) {
            
            return response()->json([
                'success' => false,
                'message'=> 'Already assigned student on this position',
            ],422);
        }



        // dd((int)$request->input('student_id'));

        $student_ref = new StudentRefer();
        $student_ref->student_login_id   = (int)$request->input('student_login_id');
        $student_ref->ref_login_id       = $request->input('ref_login_id');
        $student_ref->placement_login_id = $request->input('placement_login_id');
        $student_ref->position           = $request->input('position');
        $student_ref->net_office         = $request->input('net_office');
        $student_ref->save();


        return response()->json([
            'status' => 'success',
            'message'=> 'Student approve request sent successfully',
        ],200);


        
    }



    public function getRefData($login_id)
    {

        $ref = StudentRefer::where('net_office', $login_id)->get();

        return response()->json([
            'success' => true,
            'ref'     => $ref,
        ],200);

    }



    public function approveStudent($id)
    {
            
        $student_refer = StudentRefer::find($id);


        $student_ref = User::where('login_id', $student_refer->student_login_id)->first();
        $student_ref->student_status = 1;
        $student_ref->save();


        if(!$student_refer){
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ],404);
        }

        if ($student_refer->status == 1) {
            return response()->json([
                'success' => true,
                'message' => 'Student already approved',
            ],200);
        }


        
        if (User::where('login_id',$student_refer->net_office)->first()->balance < 6900 ) {
            
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance',
            ]);
        }


        $student_refer->status = 1;
        $student_refer->save();



        $student_ref = User::where('login_id', $student_refer->student_login_id)->first();
        $student_ref->student_status = 1;
        $student_ref->save();




        $data = StudentRefer::get();
        
        function countChildren($data, $parentId, $team) {
            $count = 0;
            foreach ($data as $item) {
                if ($item->placement_login_id == $parentId && $item->position == $team) {
                    $count++;
                    $count += countChildrenSecond($data, $item->student_login_id);
                }
            }
            return $count;
        }

        function countChildrenSecond($data, $parentId) {
            $count = 0;
            foreach ($data as $item) {
                if ($item->placement_login_id == $parentId) {
                    $count++;
                    $count += countChildrenSecond($data, $item->student_login_id);
                }
            }
            return $count;
        }
        
        function getTotalChildren($data, $parent_id) {
            $countTeamA = countChildren($data, $parent_id, 1);
            $countTeamB = countChildren($data, $parent_id, 2);
            
            return ['Team A' => $countTeamA, 'Team B' => $countTeamB];
        }
        

        $teamAChildCount = getTotalChildren($data, $student_refer->placement_login_id)['Team A'];   
        $teamBChildCount = getTotalChildren($data, $student_refer->placement_login_id)['Team B'];


     


        if ($teamAChildCount >= 10 && $teamAChildCount < 50 && $teamBChildCount >= 10 && $teamBChildCount < 50) {
            $placement_user = User::where('login_id', $student_refer->placement_login_id)->first();
            $placement_user->balance = $placement_user->balance + (int)env('REFERRAL_20_BOUNES');
            $placement_user->save();
        }

        if ($teamAChildCount >= 50 && $teamBChildCount >= 50) {
            $placement_user = User::where('login_id', $student_refer->placement_login_id)->first();
            $placement_user->balance = $placement_user->balance + (int)env('REFERRAL_50_BOUNES');
            $placement_user->save();
        }

        $ref_user = User::where('login_id', $student_refer->ref_login_id)->first();
        $ref_user->balance = $ref_user->balance + (int)env('REFERRAL_BOUNES');
    
        if($teamAChildCount > $teamBChildCount) {

            $ref_user->similer_matches = $teamBChildCount;
            $ref_user->odd_matchs      = $teamAChildCount - $teamBChildCount;

        } else {
            $ref_user->similer_matches = $teamAChildCount;
            $ref_user->odd_matchs      = $teamBChildCount - $teamAChildCount;
        }

        $ref_user->save();

        $user = User::where('login_id', $student_refer->net_office)->first();
        $user->balance -= env('GREEN_ID_CHARGE');
        $user->save();


        return response()->json([
            'success' => true,
            'message' => 'Student approved successfully',
        ],200);

        
    }
    
}
