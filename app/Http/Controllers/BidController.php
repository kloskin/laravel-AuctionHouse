<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Auction;
use App\Models\Bid;
use Carbon\Carbon;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        // 1. Sprawdzenie aktywności aukcji
        if (Carbon::now()->gte(Carbon::parse($auction->ends_at))) {
            return back()->withErrors(['error' => 'Aukcja została zakończona.']);
        }

        // 2. Walidacja kwoty (min obecna + 0.01)
        $minBid = ($auction->current_price ?? $auction->starting_price) + 0.01;
        $request->validate([
            'amount' => "required|numeric|min:{$minBid}",
        ], [
            'amount.min' => "Kwota oferty musi wynosić co najmniej {$minBid} zł.",
        ]);

        // 3. Zastosowanie logiki domenowej w modelu Bid
        $bid = Bid::place(
            $auction->id,
            auth()->id(),
            (float) $request->input('amount')
        );

        return back()->with('success', "Złożono ofertę: {$bid->amount} zł");
    }
}
