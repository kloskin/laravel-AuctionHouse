<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    protected $connection   = 'mongodb';
    protected $collection   = 'auctions';
    protected $primaryKey = '_id';

    protected $fillable     = [
        'title',
        'description',
        'starting_price',
        'current_price',
        'ends_at',
        'owner_id',
        'images',           // dodajemy pole na tablicę ścieżek
    ];
    public function bids(): HasMany
    {
        // 'auction_id' to pole w kolekcji bids, '_id' to klucz główny aukcji
        return $this->hasMany(Bid::class, 'auction_id', '_id');
    }
    // rzutowanie pola images na array
    protected $casts = [
        'images' => 'array',
        'ends_at'     => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
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
