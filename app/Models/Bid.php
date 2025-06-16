<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Bid extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bids';

    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
    ];

    public static function place(int $auctionId, int $userId, float $amount)
    {
        // zapis do MongoDB
        $bid = static::create([
            'auction_id' => $auctionId,
            'user_id'    => $userId,
            'amount'     => $amount,
        ]);

        // aktualizacja rankingów w Redis
        $zsetKey = "auction:bids:{$auctionId}";
        Redis::zadd($zsetKey, $amount, $bid->_id);

        return $bid;
    }
}
