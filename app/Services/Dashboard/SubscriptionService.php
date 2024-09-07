<?php

namespace App\Services\Dashboard;

use App\Events\SubscriptionCreated;
use App\Models\Subscription;

class SubscriptionService
{
    public function __construct(
            public LeadService $leadService,
            public PlanService   $planService,
        ) {
    }

    public function index(array $data)
    {
        return Subscription::query()->orderByDesc('id')
            ->paginate(10);
    }
    
    public function create(array $data)
    {
        $client = $this->leadService->getByPhone($data['phone']);
        $plan   = $this->planService->getById($data['plan_id']);

        $subscription = Subscription::create([
            'client_id' => $client->id,
            'plan_id'   => $data['plan_id'],
            'start_date'=> $data['start_date'],
            'end_date'  => $data['end_date'],
            'status'    => 'ACTIVE',
            'price'     => $data['amount_paid'],
            'branch_id' => $client->branch_id,
        ]);

        foreach ($plan->services as $service) {
            $subscription->services()->attach($service->id, [
                'quantity' => $service->pivot->count
            ]);
        }
        event(new SubscriptionCreated($client, $subscription));
    }

    public function delete($id): bool
    {
        return Subscription::find($id)
            ->delete();
    }
}
