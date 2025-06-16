@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Aktualne aukcje</h1>

    @if($auctions->isEmpty())
        <p class="text-gray-700">Brak dostępnych aukcji w tej chwili.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($auctions as $auction)
                <div class="border rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col">
                    @if(!empty($auction->images) && count($auction->images) > 0)
                        <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-48 object-cover rounded">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-500">Brak zdjęcia</span>
                        </div>
                    @endif

                    <h2 class="text-xl font-semibold mt-4">
                        <a href="{{ route('auctions.show', $auction->id) }}" class="hover:underline">
                            {{ $auction->title }}
                        </a>
                    </h2>

                    <p class="text-gray-600 flex-grow mt-2">{{ Str::limit($auction->description, 80) }}</p>

                    <div class="mt-4">
                        <span class="font-bold text-lg">{{ number_format($auction->starting_price, 2, ',', ' ') }} zł</span>
                    </div>

                    <div class="mt-2 text-sm text-gray-500">
                        <span>Koniec: {{ \Carbon\Carbon::parse($auction->ends_at)->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $auctions->links() }}
        </div>
    @endif
</div>
@endsection
