@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Aktualne aukcje</h1>

    @if($auctions->isEmpty())
        <p class="text-muted">Brak dostępnych aukcji w tej chwili.</p>
    @else
        <div class="row">
            @foreach($auctions as $auction)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if(!empty($auction->images) && count($auction->images) > 0)
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" class="card-img-top" alt="{{ $auction->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px;">
                                <span class="text-muted">Brak zdjęcia</span>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('auctions.show', $auction->id) }}" class="text-decoration-none">{{ $auction->title }}</a>
                            </h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($auction->description, 80) }}</p>
                            <div>
                                <span class="fw-bold">
                                    {{ number_format($auction->current_price ?? $auction->starting_price, 2, ',', ' ') }} zł
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            Koniec: {{ \Carbon\Carbon::parse($auction->ends_at)->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $auctions->links() }}
        </div>
    @endif
</div>
@endsection
