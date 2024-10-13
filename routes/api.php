<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function(){
    Route::get('/users', 'index')->name('user.index');
    Route::post('/users', 'store')->name('user.store');
    Route::get('/users/{user}', 'show')->name('user.show');
    Route::put('/users/{id}', 'update')->name('user.update');
    Route::delete('/users/{user}', 'destroy')->name('user.delete');
});

Route::controller(TransactionController::class)->group(function(){
    Route::get('/transactions', 'index')->name('transaction.index');
    Route::post('/transactions', 'store')->name('transaction.store');
    Route::get('/transactions/{transaction}', 'show')->name('transaction.show');
    Route::put('/transactions/{transaction}', 'update')->name('transaction.update');
    Route::delete('/transactions/{transaction}', 'destroy')->name('transaction.delete');
});
