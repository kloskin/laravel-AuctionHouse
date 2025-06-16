<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;

Route::get('/', [AuctionController::class, 'index'])->name('home');

Route::resource('auctions', AuctionController::class);

Route::post('auctions/{auction}/bids', [BidController::class, 'store'])
     ->name('auctions.bids.store')
     ->middleware('auth');

