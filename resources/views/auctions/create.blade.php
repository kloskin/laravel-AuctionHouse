<!-- resources/views/auctions/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Dodaj nową aukcję</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('auctions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block font-medium mb-1">Tytuł aukcji <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                   class="w-full border rounded p-2 focus:outline-none focus:ring">
        </div>

        <div>
            <label for="description" class="block font-medium mb-1">Opis aukcji</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border rounded p-2 focus:outline-none focus:ring">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="starting_price" class="block font-medium mb-1">Cena wywoławcza <span class="text-red-500">*</span></label>
            <input type="number" name="starting_price" id="starting_price" step="0.01" value="{{ old('starting_price') }}" required
                   class="w-full border rounded p-2 focus:outline-none focus:ring">
        </div>

        <div>
            <label for="ends_at" class="block font-medium mb-1">Czas zakończenia <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" required
                   class="w-full border rounded p-2 focus:outline-none focus:ring">
        </div>

        <div>
            <label for="images" class="block font-medium mb-1">Zdjęcia aukcji</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*" class="bg-secondary w-full">
            <small class="text-gray-600">Możesz dodać kilka zdjęć (jpg, png, gif). Maksymalny rozmiar pliku: 2MB.</small>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Utwórz aukcję
            </button>
        </div>
    </form>
</div>
@endsection
