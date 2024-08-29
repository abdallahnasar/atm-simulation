<?php

use App\Http\Controllers\ATMController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function (){
    Route::post('deposit', [ATMController::class, 'deposit']);
    Route::post('withdraw', [ATMController::class, 'withdraw']);
    Route::get('balance', [ATMController::class, 'balance']);
    Route::get('transactions', [ATMController::class, 'transactions']);
});

