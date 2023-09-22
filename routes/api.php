<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\CourseContentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseEnrollController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\StudentApproveController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\WithdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// auth api
Route::post('/verify/code', [AuthController::class, 'verifyCode']);
Route::post('/calculate/number', [AuthController::class, 'calculateNumber']);
Route::post('/student/register', [AuthController::class, 'studentRegister']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/year', [AuthController::class, 'year']);

// general api without auth
Route::get('student/course', [CourseController::class, 'index']);
Route::get('student/course/{id}', [CourseController::class, 'show']);
Route::get('student/event', [EventController::class, 'index']);
Route::get('student/event/{id}', [EventController::class, 'show']);

// student api
Route::middleware('auth:user-api')->group(function(){
    Route::get('/user/info', [AuthController::class, 'userInfo']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/course/enroll/{id}', [CourseEnrollController::class, 'courseEnroll']);
    Route::get('/student/course', [CourseEnrollController::class, 'studentCourse']);

    Route::post('/change/passowrd', [GeneralController::class, 'changePassword']);
    Route::post('/change/pin', [GeneralController::class, 'changePin']);
    Route::post('/set/pin', [GeneralController::class, 'setPin']);

    Route::post('/withdraw/balance', [WithdrawController::class, 'withdrawBalance']);
    Route::get('/commision/ledger', [WithdrawController::class, 'commisionLedger']);
    Route::post('/balance/transfer', [TransferController::class, 'balanceTransfer']);
    Route::get('/transcation', [TransferController::class, 'transcation']);
});





//Student ref api start
Route::post('/student/ref', [StudentApproveController::class, 'index']);
Route::post('/student/ref/approve/{id}', [StudentApproveController::class, 'approveStudent']);
Route::get('/student/ref/data/{login_id}', [StudentApproveController::class, 'getRefData']);
//Student ref api end



//Designation Api Start

Route::get('/designation', [DesignationController::class, 'index']);
Route::get('/designation/{id}', [DesignationController::class, 'show']);
Route::post('/designation', [DesignationController::class, 'store']);
Route::post('/designation/{id}', [DesignationController::class, 'edit']);
Route::delete('/designation/{id}', [DesignationController::class, 'destroy']);

//Designation Api End




// admin auth api
// Route::post('/super-admin/register', [AdminAuthController::class, 'adminRegister']);
Route::post('/sub-admin/register', [AdminAuthController::class, 'subAdminRegister']);
Route::post('/user-admin/register', [AdminAuthController::class, 'userAdminRegister']);
Route::post('/seller/register', [AdminAuthController::class, 'sellerRegister']);
Route::post('/admin/login', [AdminAuthController::class, 'adminLogin']);

// admin api
Route::middleware('auth:user-api')->group(function(){

    Route::get('/admin/info', [AdminAuthController::class, 'adminInfo']);
    Route::post('/admin/logout', [AdminAuthController::class, 'adminLogout']);
    
    Route::resource('course', CourseController::class);
    Route::resource('event', EventController::class);
    Route::resource('course/content', CourseContentController::class);


    Route::get('/user/balance', [BalanceController::class, 'userBalance']);

    Route::get('/sub-admin/list', [BalanceController::class, 'subAdminList']);


    Route::middleware('super_admin')->group(function(){
        Route::post('/balance/send', [BalanceController::class, 'BalanceSend']);
        Route::post('/withdraw/request', [BalanceController::class, 'withdrawDone']);

    });



});


Route::fallback(function(){
    return response()->json([
        'success' => false,
        "message" => 'Api not  found'
    ]);
});
