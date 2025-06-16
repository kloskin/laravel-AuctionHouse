{{-- resources/views/auctions/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container p-4">
    <h1 class="mb-4">Dodaj nową aukcję</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('auctions.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">
                Tytuł aukcji <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="title"
                   id="title"
                   value="{{ old('title') }}"
                   required
                   class="form-control">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">
                Opis aukcji
            </label>
            <textarea name="description"
                      id="description"
                      rows="4"
                      class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="starting_price" class="form-label">
                Cena wywoławcza <span class="text-danger">*</span>
            </label>
            <input type="number"
                   name="starting_price"
                   id="starting_price"
                   step="0.01"
                   value="{{ old('starting_price') }}"
                   required
                   class="form-control">
        </div>

        <div class="mb-3">
            <label for="ends_at" class="form-label">
                Czas zakończenia <span class="text-danger">*</span>
            </label>
            <input type="datetime-local"
                   name="ends_at"
                   id="ends_at"
                   value="{{ old('ends_at') }}"
                   required
                   class="form-control">
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">
                Zdjęcia aukcji
            </label>
            <input type="file"
                   name="images[]"
                   id="images"
                   multiple
                   accept="image/*"
                   class="form-control">
            <div class="form-text">
                Możesz dodać kilka zdjęć (jpg, png, gif). Maksymalny rozmiar pliku: 2 MB.
            </div>
        </div>

        <div class="d-grid">
            <button type="submit"
                    class="btn btn-primary">
                Utwórz aukcję
            </button>
        </div>
    </form>
</div>
@endsection
