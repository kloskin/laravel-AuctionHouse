<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class Auction extends Model
{
    protected $connection   = 'mongodb';
    protected $collection   = 'auctions';
    protected $fillable     = [
        'title',
        'description',
        'starting_price',
        'ends_at',
        'owner_id',
        'images',           // dodajemy pole na tablicę ścieżek
    ];

    // rzutowanie pola images na array
    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Zwraca pełne URL-e do zdjęć (public disk)
     */
    public function getImageUrlsAttribute(): array
    {
        return collect($this->images ?? [])
            ->map(fn($path) => Storage::url($path))
            ->all();
    }

    public function incrementViews()
    {
        $key = "auction:views:{$this->_id}";
        return Redis::incr($key);
    }
}
