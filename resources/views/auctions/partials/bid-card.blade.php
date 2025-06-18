@php
    // Sprawdzenie stanu aukcji
    $ended = isset($endedView) ? $endedView : \Carbon\Carbon::parse($auction->ends_at)->isPast();
    $bids = $auction->bids->sortByDesc('amount');
    $winnerBid = $bids->first();
    $winner = $winnerBid ? $winnerBid->user : null;
    $myBid = $bids->where('user_id', auth()->id())->first();
    $isWinner = $winner && ($winner->id == auth()->id());
    $isOutbid = !$ended && $myBid && $winnerBid && ($winnerBid->user_id != auth()->id());
@endphp

<div class="col-md-6 mb-4">
    <div class="card h-100 position-relative 
        @if($ended) bg-light border-secondary
        @elseif($isOutbid) border-danger
        @else border-success
        @endif">

        @if($ended)
            <span class="position-absolute top-0 end-0 badge bg-secondary m-2">Zakończona</span>
        @endif

        @if(!empty($auction->images[0]))
            <img src="{{ Storage::url($auction->images[0]) }}"
                 class="card-img-top"
                 alt="{{ $auction->title }}">
        @endif

        <div class="card-body d-flex flex-column">
            <h5 class="card-title">
                {{ $auction->title }}
                @if($ended && $isWinner)
                    <span class="badge bg-success">Wygrałeś!</span>
                @endif
            </h5>

            <p class="card-text text-truncate">{{ \Illuminate\Support\Str::limit($auction->description, 100) }}</p>

            @if($ended)
                <p class="mt-auto">
                    Oferta zwycięska:
                    <strong>{{ number_format($winnerBid->amount ?? $auction->starting_price, 2, ',', ' ') }} PLN</strong>
                </p>
                <p class="fw-bold">
                    Zwycięzca:
                    @if($winner)
                        {{ $winner->name }}
                    @else
                        brak ofert
                    @endif
                </p>
            @else
                <p class="mt-auto">
                    Obecna cena: <strong>{{ number_format($winnerBid->amount ?? $auction->starting_price, 2, ',', ' ') }} PLN</strong>
                </p>
                @if($winner)
                    <p>
                        Aktualnie prowadzi: <strong>{{ $winner->name }}</strong>
                        @if($isOutbid)
                            <span class="text-danger">(przebito Twoją ofertę)</span>
                        @endif
                    </p>
                @endif
                <div class="mt-2">
                    <small class="text-muted">Czas do końca: <span class="countdown" data-ends-at="{{ $auction->ends_at }}"></span></small>
                </div>
            @endif

            <a href="{{ route('auctions.show', $auction->_id) }}"
               class="btn btn-primary mt-2">
                Zobacz aukcję
            </a>
        </div>

        <div class="card-footer text-muted">
            @if($ended)
                Zakończono: {{ \Carbon\Carbon::parse($auction->ends_at)->locale('pl')->isoFormat('LLL') }}
            @else
                Kończy się: {{ \Carbon\Carbon::parse($auction->ends_at)->locale('pl')->isoFormat('LLL') }}
            @endif
        </div>
    </div>
</div>
