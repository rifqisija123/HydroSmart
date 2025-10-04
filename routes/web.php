<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class,'showPayPage']);
Route::post('/pay', [PaymentController::class,'createTransaction'])->name('pay');
Route::post('/midtrans/notification', [PaymentController::class,'handleNotification'])
    ->name('midtrans.notification'); // webhook