<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth')->group(function () {
     // Obsługa aukcji
     Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])
    ->name('auctions.bids.store');

    // trasa do strony „Moje oferty” i „Moje aukcje” 
    Route::get('/my-bids', [AuctionController::class, 'myBids'])
         ->name('auctions.my-bids');
     Route::get('/my-auctions', [AuctionController::class, 'myAuctions'])
          ->name('auctions.my-auctions');

    // Wyświetlenie formularza edycji profilu
    Route::get('/profile', [UserController::class, 'editProfile'])
         ->name('profile.edit');

    // Obsługa zapisu zmian w profilu
    Route::put('/profile', [UserController::class, 'updateProfile'])
         ->name('profile.update');
});