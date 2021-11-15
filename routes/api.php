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


Route::apiResources(['user' => App\Http\Controllers\API\UserController::class]);
Route::apiResources(['client' => App\Http\Controllers\API\ClientController::class]);
Route::apiResources(['pet' => App\Http\Controllers\API\PetController::class]);



Route::get('viewpet', [App\Http\Controllers\API\ClientController::class, 'PetDetails'])->name('PetDetails');
Route::get('viewpetinfo/{id}', [App\Http\Controllers\API\ClientController::class, 'PetInfo'])->name('PetInfo');

Route::get('profile', [App\Http\Controllers\API\UserController::class, 'profile'])->name('profile');
Route::put('profile', [App\Http\Controllers\API\UserController::class, 'updateProfile'])->name('updateProfile');
Route::get('findUser', [App\Http\Controllers\API\UserController::class, 'search']);

