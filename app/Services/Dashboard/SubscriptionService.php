<?php

namespace App\Services\Dashboard;

use App\Constants\CallTypes;
use App\Constants\ClientStatus;
use App\Models\Call;
use App\Models\Client;
use App\Models\Subscription;

class SubscriptionService
{
    public function __construct(
            public ClientService $clientService,
            public PlanService   $planService,
        ) {
    }


    public function create(array $data)
    {
        $client = $this->clientService->getByPhone($data['phone']);
        $plan   = $this->planService->getById($data['plan_id']);

        $subscription = Subscription::create([
            'client_id' => $client->id,
            'plan_id'   => $data['plan_id'],
            'start_date'=> $data['start_date'],
            'end_date'  => $data['end_date'],
            'status'    => 'ACTIVE',
            'price'     => $data['amount_paid'],
            'branch_id' => $client->id,
        ]);

        foreach ($plan->services as $service) {
            $subscription->services()->attach($service->id, [
                'quantity' => $service->count
            ]);
        }

        if($client->client_status != ClientStatus::CONVERTED)
        {
            $client->client_status = ClientStatus::CONVERTED;
            $client->save();
            Call::where('client_id',$client->id)->where('status',null)->delete();
        }
    }
}
