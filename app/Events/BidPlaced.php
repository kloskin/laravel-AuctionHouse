<?php

namespace App\Events;

use App\Models\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Bid $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function broadcastOn(): Channel
    {
        return new Channel("auction.{$this->bid->auction_id}");
    }

    public function broadcastWith(): array
    {
        return [
            'id'        => $this->bid->id,
            'amount'    => number_format($this->bid->amount, 2, ',', ' '),
            'bidder'    => $this->bid->user->name,       // załóżmy relację user()
            'createdAt' => $this->bid->created_at->format('H:i:s'),
        ];
    }
}
