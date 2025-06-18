@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Moje aukcje</h1>

    @php
        use Carbon\Carbon;
        $activeAuctions = $auctions->filter(fn($a) => !Carbon::parse($a->ends_at)->isPast());
        $endedAuctions  = $auctions->filter(fn($a) => Carbon::parse($a->ends_at)->isPast());
    @endphp

    @if($activeAuctions->isEmpty() && $endedAuctions->isEmpty())
        <div class="alert alert-info">Nie masz jeszcze żadnych aukcji.</div>
    @else
        <ul class="nav nav-tabs mb-4" id="myAuctionsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                    Trwające ({{ $activeAuctions->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ended-tab" data-bs-toggle="tab" data-bs-target="#ended" type="button" role="tab" aria-controls="ended" aria-selected="false">
                    Zakończone ({{ $endedAuctions->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myAuctionsTabContent">
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                @if($activeAuctions->isEmpty())
                    <div class="alert alert-secondary">Brak trwających aukcji.</div>
                @else
                    <div class="row">
                        @foreach($activeAuctions as $auction)
                            @php
                                $bids = $auction->bids->sortByDesc('amount');
                                $highestBid = $bids->first();
                                $highestBidder = $highestBid ? $highestBid->user : null;
                                $bidCount = $bids->count();
                            @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-success position-relative">
                                    @if(!empty($auction->images[0]))
                                        <img src="{{ Storage::url($auction->images[0]) }}" class="card-img-top" alt="{{ $auction->title }}">
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $auction->title }}</h5>
                                        <p class="card-text text-truncate">{{ \Illuminate\Support\Str::limit($auction->description, 100) }}</p>
                                        <p class="mt-auto">Licytacji: {{ $bidCount }}</p>
                                        <p>Aktualna cena: <strong>{{ number_format($highestBid->amount ?? $auction->starting_price, 2, ',', ' ') }} PLN</strong></p>
                                        @if($highestBidder)
                                            <p>Aktualnie prowadzi: <strong>{{ $highestBidder->name }}</strong></p>
                                        @endif
                                        <div class="mt-2">
                                            <small class="text-muted">Czas do końca: <span class="countdown" data-ends-at="{{ $auction->ends_at }}"></span></small>
                                        </div>
                                        <a href="{{ route('auctions.show', $auction->_id) }}" class="btn btn-primary mt-2">Zobacz aukcję</a>
                                    </div>
                                    <div class="card-footer text-muted">
                                        Kończy się: {{ Carbon::parse($auction->ends_at)->locale('pl')->isoFormat('LLL') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="tab-pane fade" id="ended" role="tabpanel" aria-labelledby="ended-tab">
                @if($endedAuctions->isEmpty())
                    <div class="alert alert-secondary">Brak zakończonych aukcji.</div>
                @else
                    <div class="row">
                        @foreach($endedAuctions as $auction)
                            @php
                                $bids = $auction->bids->sortByDesc('amount');
                                $highestBid = $bids->first();
                                $winner = $highestBid ? $highestBid->user : null;
                                $bidCount = $bids->count();
                            @endphp
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 bg-light border-secondary position-relative">
                                    <span class="position-absolute top-0 end-0 badge bg-secondary m-2">Zakończona</span>
                                    @if(!empty($auction->images[0]))
                                        <img src="{{ Storage::url($auction->images[0]) }}" class="card-img-top" alt="{{ $auction->title }}">
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $auction->title }}</h5>
                                        <p class="card-text text-truncate">{{ \Illuminate\Support\Str::limit($auction->description, 100) }}</p>
                                        <p class="mt-auto">Licytacji: {{ $bidCount }}</p>
                                        <p>Ostateczna cena: <strong>{{ number_format($highestBid->amount ?? $auction->starting_price, 2, ',', ' ') }} PLN</strong></p>
                                        <p class="fw-bold">Zwycięzca: <strong>{{ $winner?->name ?? 'brak ofert' }}</strong></p>
                                        <a href="{{ route('auctions.show', $auction->_id) }}" class="btn btn-primary mt-2">Zobacz aukcję</a>
                                    </div>
                                    <div class="card-footer text-muted">
                                        Zakończono: {{ Carbon::parse($auction->ends_at)->locale('pl')->isoFormat('LLL') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const els = document.querySelectorAll('.countdown');
        if (!els.length) return;
        function updateAll() {
            const now = new Date();
            els.forEach(el => {
                const endsAt = new Date(el.dataset.endsAt);
                let diff = Math.max(0, Math.floor((endsAt - now) / 1000));
                const h = String(Math.floor(diff / 3600)).padStart(2, '0');
                diff %= 3600;
                const m = String(Math.floor(diff / 60)).padStart(2, '0');
                const s = String(diff % 60).padStart(2, '0');
                el.textContent = `${h}:${m}:${s}`;
            });
        }
        updateAll();
        setInterval(updateAll, 1000);
    })();
</script>
@endpush
