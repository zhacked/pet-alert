<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// resource
Route::apiResources(['user' => App\Http\Controllers\API\UserController::class]);
Route::apiResources(['client' => App\Http\Controllers\API\ClientController::class]);
Route::apiResources(['pet' => App\Http\Controllers\API\PetController::class]);
Route::apiResources(['employeess' => App\Http\Controllers\API\EmployeeController::class]);
Route::apiResources(['report' => App\Http\Controllers\API\ReportController::class]);
Route::apiResources(['service' => App\Http\Controllers\API\ServiceController::class]);
Route::apiResources(['schedule' => App\Http\Controllers\API\ScheduleController::class]);
// Route::apiResources(['sms' => App\Http\Controllers\API\SmsController::class]);

// custom route
Route::get('viewpet', [App\Http\Controllers\API\ClientController::class, 'PetDetails'])->name('PetDetails');
Route::get('viewpetinfo/{id}', [App\Http\Controllers\API\ClientController::class, 'PetInfo'])->name('PetInfo');
Route::get('showpet/{id}', [App\Http\Controllers\API\ClientController::class, 'showPet'])->name('showPet');
Route::get('profile', [App\Http\Controllers\API\UserController::class, 'profile'])->name('profile');
Route::put('profile', [App\Http\Controllers\API\UserController::class, 'updateProfile'])->name('updateProfile');
Route::get('userLoginDetails', [App\Http\Controllers\API\UserController::class, 'userLogin']);
Route::get('countdata', [App\Http\Controllers\API\UserController::class, 'countuser'])->name('countuser');
Route::get('record', [App\Http\Controllers\API\ScheduleController::class, 'petrecord']);
Route::get('ownerPet', [App\Http\Controllers\API\PetController::class, 'petRecord'])->name('petRecord');
Route::get('eventSchedule', [App\Http\Controllers\API\ScheduleController::class, 'eventSchedule'])->name('eventSchedule');
Route::get('custom_employee', [App\Http\Controllers\API\EmployeeController::class, 'customemployees'])->name('customemployees');
Route::get('getPet/{id}', [App\Http\Controllers\API\PetController::class, 'getPet']);
Route::get('reportAcceptance/{id}/{status}', [App\Http\Controllers\API\ScheduleController::class, 'acceptancereport']);
Route::post('destroySched', [App\Http\Controllers\API\ScheduleController::class, 'destroySched']);
Route::post('smsSend', [App\Http\Controllers\API\SmsController::class, 'smsSend']);
Route::post('destroySched', [App\Http\Controllers\API\ScheduleController::class, 'destroySched']);
Route::get('userdeleted', [App\Http\Controllers\API\UserController::class, 'userdeletedindex']);
Route::get('userdelete/{id}', [App\Http\Controllers\API\UserController::class, 'userdeletedpermenent']);
Route::get('useractivate/{id}', [App\Http\Controllers\API\UserController::class, 'useractivateagain']);

?>