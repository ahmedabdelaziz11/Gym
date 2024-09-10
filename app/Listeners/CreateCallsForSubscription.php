<?php

namespace App\Listeners;

use App\Enums\CallTypes;
use App\Events\SubscriptionCreated;
use App\Services\Dashboard\CallService;
use Carbon\Carbon;

class CreateCallsForSubscription
{
    public $callService;
    /**
     * Create the event listener.
     */
    public function __construct(CallService $callService)
    {
        $this->callService = $callService;
    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionCreated $event):void
    {
        $client = $event->client;
        $subscription = $event->subscription;

        $endDate   = Carbon::parse($subscription->end_date);
        $startDate = Carbon::parse($subscription->start_date);

        $this->callService->create([
            'client_id'  => $client->id,
            'type'       => CallTypes::RENEWAL,
            'date'       => $endDate->subDays(3),
            'status'     => null,
            'branch_id'  => $client->branch_id,
        ]);
        $this->callService->create([
            'client_id'  => $client->id,
            'type'       => CallTypes::UPGRADE,
            'date'       => $startDate->addWeeks(2),
            'status'     => null,
            'branch_id'  => $client->branch_id,
        ]);

        $endDate   = Carbon::parse($subscription->end_date);
        $startDate = Carbon::parse($subscription->start_date);
        while ($startDate->lt($endDate)) {
            $startDate->addDays(30);
            $this->callService->create([
                'client_id'  => $client->id,
                'type'       => CallTypes::FEEDBACK,
                'date'       => $startDate->copy(),
                'status'     => null,
                'branch_id'  => $client->branch_id,
            ]);
        }
    }
}
