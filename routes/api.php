<?php

use App\Http\Controllers\Api\CaseController;
use App\Http\Controllers\Api\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (\Illuminate\Http\Request $request) => $request->user());

    Route::apiResource('cases', CaseController::class)->parameters(['cases' => 'case']);
    Route::apiResource('clients', ClientController::class);
});
