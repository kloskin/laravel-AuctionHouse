<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [AuctionController::class, 'index'])->name('home');
// Rejestracja
Route::get('register', [RegisterController::class, 'showRegistrationForm'])
     ->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Logowanie / Wylogowanie
Route::get('login', [LoginController::class, 'showLoginForm'])
     ->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])
     ->name('logout');

Route::resource('auctions', AuctionController::class);

Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])
    ->name('auctions.bids.store')
    ->middleware('auth');