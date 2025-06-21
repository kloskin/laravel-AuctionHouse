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
        if (Carbon::now()->gte($auction->ends_at)) {
            return back()->withErrors(['error' => 'Aukcja została zakończona.']);
        }

        // 2. Walidacja kwoty (min obecna + 0.01)
        $minBid = ($auction->current_price ?? $auction->starting_price) + 0.01;
        $request->validate([
            'amount' => "required|numeric|min:{$minBid}",
        ], [
            'amount.min' => "Kwota oferty musi wynosić co najmniej {$minBid} zł.",
        ]);

        // 3. Zapis oferty
        $bid = Bid::place(
            $auction->id,
            auth()->id(),
            (float) $request->input('amount')
        );

        // 4. Rozszerzenie aukcji o 30 s tylko, gdy do końca zostało ≤60 s

        // dd([
        //     'now' => $now,
        //     'endsAt' => $endsAt,
        //     'remaining' => $remaining,
        // ]);

        // 5. Broadcast zdarzenia
        broadcast(new \App\Events\PriceUpdated($bid->auction_id, $bid->amount));

        // 2) A zaraz potem puszczaj BidPlaced, żeby zaktualizować historię
        broadcast(new \App\Events\BidPlaced($bid));
        
        $now    = Carbon::now('Europe/Warsaw');
        $endsAt = Carbon::parse($auction->ends_at)->setTimezone('Europe/Warsaw');

        $remaining = $endsAt->timestamp - $now->timestamp;

        if ($remaining > 0 && $remaining <= 60) {
            $auction->ends_at = $endsAt->addSeconds(30);
            $auction->save();
        }

        return back()->with('success', "Złożono ofertę: {$bid->amount} zł");
    }

}
