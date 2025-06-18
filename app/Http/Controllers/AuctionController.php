<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sortujemy po ends_at (pole w modelu), 12 na stronę
        $auctions = Auction::where('ends_at', '>', Carbon::now())
                           ->orderBy('ends_at', 'asc')
                           ->paginate(12);

        return view('auctions.index', compact('auctions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auctions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string',
            'description'    => 'nullable|string',
            'starting_price' => 'required|numeric',
            'ends_at'        => 'required|date|after:now',
            'images.*'       => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data['owner_id'] = Auth::id();

        // Obsługa uploadu zdjęć
        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('auctions', 'public');
            }
            $data['images'] = $paths;
        }

        Auction::create($data);

        return redirect()->route('auctions.index')
                         ->with('success', 'Aukcja utworzona!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        // $auction już załadowany, nie musimy szukać po ID
        $views = $auction->incrementViews();

        return view('auctions.show', [
            'auction' => $auction,
            'views'   => $views,
            'images'  => $auction->image_urls,  // accessor z modelu
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        // możesz tutaj też sprawdzić policy:
        $this->authorize('update', $auction);

        return view('auctions.edit', compact('auction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        $this->authorize('update', $auction);

        $data = $request->validate([
            'title'          => 'required|string',
            'description'    => 'nullable|string',
            'starting_price' => 'required|numeric',
            'ends_at'        => 'required|date|after:now',
            'images.*'       => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Doklejamy nowe zdjęcia do istniejących
        if ($request->hasFile('images')) {
            $new = [];
            foreach ($request->file('images') as $file) {
                $new[] = $file->store('auctions', 'public');
            }
            $data['images'] = array_merge($auction->images ?? [], $new);
        }

        $auction->update($data);

        return redirect()->route('auctions.show', $auction)
                         ->with('success', 'Aukcja zaktualizowana!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction)
    {
        $this->authorize('delete', $auction);

        // (opcjonalnie) usuń pliki zdjęć z dysku
        foreach ($auction->images ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $auction->delete();

        return redirect()->route('auctions.index')
                         ->with('success', 'Aukcja usunięta!');
    }
    public function myAuctions(){
        $auctions = Auction::where('owner_id', auth()->id())
                        ->orderBy('ends_at','asc')
                        ->get();
        return view('auctions.my-auctions', compact('auctions'));
    }

    public function myBids()
    {
        $userId = Auth::id();

        // 1) Możesz pobrać wszystkie auction_id z modelu Bid:
        $auctionIds = Bid::where('user_id', $userId)
                         ->pluck('auction_id')
                         ->unique()
                         ->toArray();

        // 2) Załaduj aukcje o tych ID:
        $auctions = Auction::whereIn('_id', $auctionIds)
                           ->orderBy('ends_at', 'asc')
                           ->get();

        return view('auctions.my-bids', compact('auctions'));
    }
}
