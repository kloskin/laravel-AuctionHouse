@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Zdjęcia aukcji -->
        <div class="col-md-6">
            @if(!empty($auction->images) && count($auction->images) > 0)
                <div id="auctionCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($auction->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="{{ $auction->title }}" style="height: 400px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#auctionCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Poprzednie</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#auctionCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Następne</span>
                    </button>
                </div>
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:400px;">
                    <span class="text-muted">Brak zdjęcia</span>
                </div>
            @endif
        </div>

        <!-- Szczegóły aukcji i formularz licytacji -->
        <div class="col-md-6">
            <h1>{{ $auction->title }}</h1>

            @php
                $now = \Carbon\Carbon::now();
                $endsAt = \Carbon\Carbon::parse($auction->ends_at);
                $isEnded = $now->gte($endsAt);
                $highestBid = $auction->bids->sortByDesc('amount')->first();
            @endphp

            @if($isEnded)
                <h5 class="text-success">Aukcja zakończona</h5>
                <p class="mb-1">
                    <strong>Data zakończenia:</strong>
                    {{ $endsAt->format('d.m.Y H:i:s') }}
                </p>
                @if($highestBid)
                    <p>
                        <strong>Zwycięzca:</strong> {{ $highestBid->user->name }}<br>
                        <strong>Oferta zwycięska:</strong> {{ number_format($highestBid->amount, 2, ',', ' ') }} zł
                    </p>
                @else
                    <p class="text-muted">Brak ofert w aukcji.</p>
                @endif
            @else
                <!-- Countdown do końca aukcji -->
                <h5 class="text-muted">Czas do końca aukcji:</h5>
                <p class="fs-5 fw-bold">
                    <span id="countdown" data-ends-at="{{ $auction->ends_at }}"></span>
                    <span class="text-muted"> ({{ $endsAt->format('d.m.Y H:i') }})</span>
                </p>
                
                <!-- Cena wywoławcza -->
                <h5 class="mt-4">Cena wywoławcza:</h5>
                <p class="fs-4 fw-bold">{{ number_format($auction->starting_price, 2, ',', ' ') }} zł</p>

                <!-- Aktualna najwyższa oferta -->
                @if(isset($auction->current_price))
                    <h5>Aktualna najwyższa oferta:</h5>
                    <p class="fs-4 text-danger fw-bold">{{ number_format($auction->current_price, 2, ',', ' ') }} zł</p>
                @endif

                <!-- Formularz licytacji widoczny tylko dla zalogowanych -->
                @auth
                    <form action="{{ route('auctions.bids.store', $auction) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Twoja oferta (zł)</label>
                            <input type="number" step="0.01" min="{{ ($auction->current_price ?? $auction->starting_price) + 0.01 }}" class="form-control" id="amount" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Złóż ofertę</button>
                    </form>
                @else
                    <p class="text-muted mt-4">Aby złożyć ofertę, <a href="{{ route('login') }}">zaloguj się</a>.</p>
                @endauth
            @endif
        </div>
    </div>

    <!-- Opis aukcji -->
    <div class="row mt-5">
        <div class="col">
            <h4>Opis</h4>
            <p>{{ $auction->description }}</p>
        </div>
    </div>

    <!-- Historia licytacji -->
    <div class="row mt-5">
        <div class="col">
            <h4>Historia licytacji</h4>
            @if($auction->bids->isEmpty())
                <p class="text-muted">Brak ofert.</p>
            @else
                <ul class="list-group">
                    @foreach($auction->bids as $bid)
                        @php
                            $isUserHighest = auth()->check() && $highestBid && $bid->id === $highestBid->id && $bid->user_id === auth()->id();
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center {{ $isUserHighest ? 'bg-success text-white' : '' }}">
                            <div class="d-flex align-items-center">
                                @if($isUserHighest)
                                    <i class="bi bi-star-fill me-2"></i>
                                @endif
                                <div>
                                    <strong>{{ $bid->user->name }}</strong><br>
                                    <small class="{{ $isUserHighest ? 'text-white' : 'text-muted' }}">{{ $bid->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                            </div>
                            <span class="fw-bold">{{ number_format($bid->amount, 2, ',', ' ') }} zł</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const el = document.getElementById('countdown');
        if (!el) return;
        const endsAt = new Date(el.dataset.endsAt);
        let interval;
        function update() {
            const now = new Date();
            let diff = Math.max(0, Math.floor((endsAt - now) / 1000));
            const h = String(Math.floor(diff / 3600)).padStart(2, '0');
            diff %= 3600;
            const m = String(Math.floor(diff / 60)).padStart(2, '0');
            const s = String(diff % 60).padStart(2, '0');
            el.textContent = `${h}:${m}:${s}`;
            if (h === '00' && m === '00' && s === '00') clearInterval(interval);
        }
        update();
        interval = setInterval(update, 1000);
    })();
</script>
@endpush
