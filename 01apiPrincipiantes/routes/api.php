<?php

use App\Http\Controllers\_SiteController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [_SiteController::class,'apiIndex'])->name('apiIndex');


Route::apiResource('/clients', ClientController::class)->names('api.clients');
















/*  */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
