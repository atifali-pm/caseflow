<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing');

Route::middleware('auth')->group(function () {
    Route::post('/subscribe/{plan}', [SubscriptionController::class, 'checkout'])->name('subscribe');
    Route::get('/subscribe/success', [SubscriptionController::class, 'success'])->name('subscribe.success');
    Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing-portal');
});
