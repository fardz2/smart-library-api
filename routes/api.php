<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Buku\BukuController;
use App\Http\Controllers\Role\RoleController;
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
Route::get('info',[AuthController::class, 'getInfo']);
Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);
Route::get("buku",[BukuController::class, 'index']);
Route::get("buku/{id}",[BukuController::class, 'show']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/role', [RoleController::class, 'index']);
    Route::post('/role', [RoleController::class, 'store']);
    Route::delete('/role/{id}', [RoleController::class, 'destroy']);
    Route::get('/info',[AuthController::class, 'getInfo']);
    Route::post('/buku',[BukuController::class, 'store']);
    Route::put('/buku/{id}',[BukuController::class, 'update']);
    Route::delete('/buku/{id}',[BukuController::class, 'destroy']);
});
