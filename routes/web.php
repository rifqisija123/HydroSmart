<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Models\Transaction;

Route::get('/', [PaymentController::class,'showDrinkPage'])->name('home');
Route::get('/success', [PaymentController::class, 'showSuccess'])->name('success');
Route::get('/close', function (Illuminate\Http\Request $request) {
    return view('close');
})->name('close');
Route::get('/{drink}', [PaymentController::class,'showPayPage'])->name('volume');
Route::get('/{drink}/detail/{ml}', [PaymentController::class,'showDetail'])->name('detail');
Route::post('/{drink}/pay', [PaymentController::class,'createTransaction'])->name('pay');

Route::get('admin/transactions/print', function () {
    $filter = request()->all();

    $query = Transaction::query();

    if (isset($filter['tableFilters'])) {
        // TODO: apply filters
    }

    $data = $query->get();

    return view('exports.transactions-print', [
        'records' => $data
    ]);
})->name('transactions.print');
