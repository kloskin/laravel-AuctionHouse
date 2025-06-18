<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $auctionId;
    public float  $newPrice;

    /**
     * Create a new event instance.
     */
    public function __construct(string $auctionId, float $newPrice)
    {
        $this->auctionId = $auctionId;
        $this->newPrice  = $newPrice;
    }
    public function broadcastWith(): array
    {
        return [
            'auctionId' => $this->auctionId,
            'newPrice' => number_format($this->newPrice, 2, ',', ' '),
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel
    {
        // używamy stringa, np. "auction.68529d6d4f3384fd21026de2"
        return new Channel("auction.{$this->auctionId}");
    }
}
