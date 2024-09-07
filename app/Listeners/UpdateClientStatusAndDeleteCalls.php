<?php

namespace App\Listeners;

use App\Constants\ClientStatus;
use App\Events\SubscriptionCreated;
use App\Models\Call;

class UpdateClientStatusAndDeleteCalls
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event):void
    {
        $client = $event->client;

        if ($client->client_status != ClientStatus::CONVERTED) {
            $client->client_status  = ClientStatus::CONVERTED;
            $client->save();

            Call::where('client_id', $client->id)->where('status', null)->delete();
        }
    }
}
