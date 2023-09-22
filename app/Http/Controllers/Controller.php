<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function successWithResponse($message = 'Successfully done',$code = 200, $data = null)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => true,
            'status_code' => $code
        ],$code);
    } 
    public static function errorWithResponse($message = 'Data not found',$code = 404, $data = null)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => false,
            'status_code' => $code
        ],$code);
    } 

}
