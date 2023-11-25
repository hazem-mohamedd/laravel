<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIs\DriveController;
use App\Http\Controllers\UserController;



Route::post("register" , [UserController::class, 'register']);
Route::post("login" , [UserController::class, 'login']);

Route::middleware("auth:sanctum")->group(function () {


Route::get("logout" , [UserController::class, 'logout']);


Route::prefix("drive")->group(function (){

    Route::get("/MyFiles/{id}",[DriveController::class , 'MyFiles']);
    Route::get("/publicFile",[DriveController::class , 'publicFile']);
    Route::get("/allFile",[DriveController::class , 'allFile']);
    Route::post("/store/{id}" , [DriveController::class , 'store']);

    // Route With ID
    Route::get("/show/{id}" , [DriveController::class , 'show']);
    Route::post("/update/{id}" , [DriveController::class , 'update']);
    Route::get("/destroy/{id}" , [DriveController::class , 'destroy']);
    Route::get("download/{id}" , [DriveController::class , 'download']);
    Route::get("changeStatus/{id}" , [DriveController::class , 'changeStatus']);
   });


});
