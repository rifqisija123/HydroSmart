<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//webhook midtrans
Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification'])
    ->name('midtrans.notification');

Route::get('/device/poll', [PaymentController::class, 'devicePoll'])
    ->name('device.poll');
