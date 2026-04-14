<?php

use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\Portal\PortalAuthController;
use App\Http\Controllers\Portal\PortalController;
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
    Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'show'])->name('invoice.pdf');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [PortalAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PortalAuthController::class, 'login'])->name('login.submit');
    Route::get('/register/{token}', [PortalAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [PortalAuthController::class, 'register'])->name('register.store');
    Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:client'])->group(function () {
        Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/cases', [PortalController::class, 'cases'])->name('cases');
        Route::get('/cases/{caseRecord}', [PortalController::class, 'showCase'])->name('cases.show');
        Route::get('/invoices', [PortalController::class, 'invoices'])->name('invoices');
    });
});
