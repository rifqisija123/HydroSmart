<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', [PaymentController::class,'showPayPage'])->name('home');
Route::get('/detail/{ml}', [PaymentController::class,'showDetail'])->name('detail');
Route::post('/pay', [PaymentController::class,'createTransaction'])->name('pay');
Route::get('/success', [PaymentController::class, 'showSuccess'])->name('success');