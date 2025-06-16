<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, $auctionId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $bid = Bid::place(
            $auctionId,
            auth()->id(),
            (float) $request->input('amount')
        );

        return back()->with('success', "Złożono ofertę: {$bid->amount}");
    }
}
