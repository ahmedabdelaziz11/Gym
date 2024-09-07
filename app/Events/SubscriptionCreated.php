<?php

namespace App\Events;

use App\Models\Client;
use App\Models\Subscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;
    public $subscription;

    public function __construct(Client $client, Subscription $subscription)
    {
        $this->client = $client;
        $this->subscription = $subscription;
    }
}
