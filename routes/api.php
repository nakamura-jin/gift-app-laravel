<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;

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

Route::middleware('auth:user, owner, admin')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'me']);

//Genre
Route::get('genre', [MenuController::class, 'genre']);

// //Menu
Route::get('menu', [MenuController::class, 'index']);
Route::post('menu', [MenuController::class, 'store']);
Route::post('menu/upload', [MenuController::class, 'upload']);
Route::get('menu/{id}', [MenuController::class, 'show']);
Route::put('menu/{id}', [MenuController::class, 'update']);
Route::delete('menu/{id}', [MenuController::class, 'destroy']);
Route::put('menu/{owner_id}/update_stock', [MenuController::class, 'recievedStock']);

//owner
Route::get('owner', [OwnerController::class, 'index']);
Route::get('owner/menu/{owner_id}', [MenuController::class, 'myMenu']);