@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Aukcje, w których złożyłeś ofertę</h1>

    @php
        // Podział aukcji na trwające i zakończone
        $activeAuctions = $auctions->filter(fn($a) => !\Carbon\Carbon::parse($a->ends_at)->isPast());
        $endedAuctions  = $auctions->filter(fn($a) =>  \Carbon\Carbon::parse($a->ends_at)->isPast());
    @endphp

    @if($activeAuctions->isEmpty() && $endedAuctions->isEmpty())
        <div class="alert alert-info">
            Nie złożyłeś jeszcze żadnej oferty.
        </div>
    @else
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-4" id="myBidsTab" role="tablist">
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

        <div class="tab-content" id="myBidsTabContent">
            <!-- Trwające aukcje -->
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                @if($activeAuctions->isEmpty())
                    <div class="alert alert-info">Brak trwających aukcji.</div>
                @else
                    <div class="row">
                        @foreach($activeAuctions as $auction)
                            @include('auctions.partials.bid-card', ['auction' => $auction])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Zakończone aukcje -->
            <div class="tab-pane fade" id="ended" role="tabpanel" aria-labelledby="ended-tab">
                @if($endedAuctions->isEmpty())
                    <div class="alert alert-info">Brak zakończonych aukcji.</div>
                @else
                    <div class="row">
                        @foreach($endedAuctions as $auction)
                            @include('auctions.partials.bid-card', ['auction' => $auction, 'endedView' => true])
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
