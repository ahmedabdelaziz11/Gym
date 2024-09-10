<?php

namespace App\Events;

use App\Models\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadInterestedAfterFirstVisit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;
    public $nextCallDate;

    /**
     * Create a new event instance.
     */
    public function __construct(Client $client, $nextCallDate)
    {
        $this->client = $client;
        $this->nextCallDate = $nextCallDate;
    }
}
