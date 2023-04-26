<?php

use App\Http\Controllers\Api\v1\ClientController;
use App\Http\Controllers\Api\v1\InvitationController;
use App\Http\Controllers\Api\v1\ProjectController;
use App\Http\Controllers\Api\v1\TimeLogController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// public routes
Route::post("/register",[AuthController::class,"register"])->name("register");
Route::post("/login",[AuthController::class,"login"]);

//protected routes

Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post("/logout",[AuthController::class,"logout"]);

    //admin routes
    Route::group(['middleware' => 'permission:Is Admin'],function(){
        Route::get('/users',[UserController::class,"index"]);
        Route::resource("/clients",ClientController::class);
        Route::resource("/projects",ProjectController::class);
        Route::post("/invite",[InvitationController::class,"invite"]);
        Route::post("/invite/resend",[InvitationController::class,"resend"]);
    });

    Route::get("/time-logs/{user}",[TimeLogController::class,"index"]);
    Route::post("/time-log/start",[TimeLogController::class,"start"]);
    Route::post("/time-log/{timeLog}/end",[TimeLogController::class,"end"]);
});
