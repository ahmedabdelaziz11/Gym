<?php

namespace App\Listeners;

use App\Enums\CallTypes;
use App\Enums\ClientStatus;
use App\Events\LeadInterestedAfterFirstVisit;
use App\Services\Dashboard\CallService;

class CreateNextCallForLead
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
    public function handle(LeadInterestedAfterFirstVisit $event): void
    {
        $excludedStatuses = [
            ClientStatus::NOT_INTERESTED->value,
            ClientStatus::CONVERTED->value,
            ClientStatus::NOT_QUALIFIED->value
        ];
    
        if (!in_array($event->client->client_status, $excludedStatuses)) {
            $this->callService->create([
                'client_id' => $event->client->id,
                'Type'      => CallTypes::FIRST_CALL->value,
                'date'      => $event->nextCallDate ?? now()->addDay(),
                'branch_id' => $event->client->branch_id,
            ]);
        }
    }
}
