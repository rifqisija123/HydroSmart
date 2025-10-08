<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class,'showPayPage'])->name('home');
Route::get('/detail/{ml}', [PaymentController::class,'showDetail'])->name('detail');
Route::post('/pay', [PaymentController::class,'createTransaction'])->name('pay');
Route::get('/success', [PaymentController::class, 'showSuccess'])->name('success');
Route::post('/midtrans/notification', [PaymentController::class,'handleNotification'])->name('midtrans.notification'); // webhook
Route::get('/device/poll', [PaymentController::class,'devicePoll'])->name('device.poll');