<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');


Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::resource('users', UserController::class);
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

});
