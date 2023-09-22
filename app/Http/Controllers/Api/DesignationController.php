<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function index(){

        $designation = Designation::get();

        return response()->json([
            'success' => true,
            'data' => $designation,
        ],200);
    }


    public function store(Request $request){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'total_conection' => 'required|integer',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        Designation::create([
            'name' => $request->name,
            'total_conection' => $request->total_conection
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully created',
        ],200);
    }


    public function show($id){

        $designation = Designation::find($id);

        return response()->json([
            'success' => true,
            'data'    => $designation,
        ],200);
    }



    public function edit(Request $request,$id){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'total_conection' => 'required|integer',
        ]);

        if ($validation->fails()) {
            return self::errorWithResponse('validation failed', 422, $validation->errors());
        }

        $deg = Designation::find($id);
        if(!$deg){
            return response()->json([
                'success' => false,
                'message' => 'Designation not found',
            ], 404);
        }
        $deg->update([
            'name'         => $request->name,
            'total_conection' => $request->total_conection
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated',
        ],200);
    }




    
    public function destroy($id){

        $designation = Designation::find($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully Deleted',
        ],200);
    }


}
