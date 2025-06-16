<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bids';
    protected $primaryKey = '_id';

    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public static function place(int $auctionId, int $userId, float $amount): self
    {
        // 1. Zapisz nową ofertę w MongoDB
        $bid = self::create([
            'auction_id' => $auctionId,
            'user_id'    => $userId,
            'amount'     => $amount,
        ]);

        // 2. Aktualizacja aktualnej ceny w dokumencie Auction
        $auction = Auction::findOrFail($auctionId);
        $auction->update(['current_price' => $amount]);

        // 3. Dodaj do Redis zset aukcji
        $redisKey = "auction:{$auctionId}:bids";
        Redis::zadd($redisKey, [$bid->id => $amount]);

        return $bid;
    }
}
