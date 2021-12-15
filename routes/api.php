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

// custom route
Route::get('viewpet', [App\Http\Controllers\API\ClientController::class, 'PetDetails'])->name('PetDetails');
Route::get('viewpetinfo/{id}', [App\Http\Controllers\API\ClientController::class, 'PetInfo'])->name('PetInfo');
Route::get('showpet/{id}', [App\Http\Controllers\API\ClientController::class, 'showPet'])->name('showPet');
Route::get('ownerPet', [App\Http\Controllers\API\PetController::class, 'petRecord'])->name('petRecord');

Route::get('profile', [App\Http\Controllers\API\UserController::class, 'profile'])->name('profile');
Route::put('profile', [App\Http\Controllers\API\UserController::class, 'updateProfile'])->name('updateProfile');
Route::get('findUser', [App\Http\Controllers\API\UserController::class, 'search']);
Route::get('countdata', [App\Http\Controllers\API\UserController::class, 'countuser'])->name('countuser');
Route::get('record', [App\Http\Controllers\API\ReportController::class, 'petrecord']);
Route::get('schedule', [App\Http\Controllers\API\ScheduleController::class, 'schedule'])->name('Schedule');
Route::get('custom_employee', [App\Http\Controllers\API\EmployeeController::class, 'customemployees'])->name('customemployees');
Route::get('petOwnerBase/{id}', [App\Http\Controllers\API\ReportController::class, 'OnwerBasePet']);







?>