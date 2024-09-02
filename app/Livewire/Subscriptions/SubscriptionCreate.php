<?php

namespace App\Livewire\Subscriptions;

use App\Services\Dashboard\ClientService;
use App\Services\Dashboard\PlanService;
use App\Services\Dashboard\SubscriptionService;
use Livewire\Component;

class SubscriptionCreate extends Component
{
    public $client_id;
    public $client_name;
    public $phone;
    public $plan_id;
    public $start_date;
    public $end_date;
    public $amount_paid;


    protected function rules()
    {
        return  [
            'phone' => 'required|string|exists:clients,phone',
            'plan_id' => 'required|integer|exists:plans,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount_paid' => 'required|numeric|min:0',
        ];
    }

    public function updatedPhone($value,ClientService $clientService)
    {
        $client = $clientService->getByPhone($value);
        if ($client) {
            $this->client_id = $client->id;
            $this->client_name = $client->name;
        } else {
            $this->client_id = null;
            $this->client_name = null;
        }
    }

    public function updatedStartDate($value,PlanService $planService)
    {
        $plan = $planService->getById($this->plan_id);
        if ($plan) {
            $this->end_date = \Carbon\Carbon::parse($this->start_date)
            ->addDays($plan->days)
            ->format('Y-m-d');
        } else {
            $this->end_date = null;
        }
    }

    public function updatedPlanId($value,PlanService $planService)
    {
        $plan = $planService->getById($value);
    
        if ($plan) {
            $this->amount_paid = $plan->cost;
            $this->plan_id = $plan->id;
        } else {
            $this->amount_paid = null;
        }
    }

    public function save(SubscriptionService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('subscription-create')) {
            abort(403, 'Unauthorized');
        }
        $this->validate();
        $service->create([
            'phone' => $this->phone,
            'plan_id' => $this->plan_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'amount_paid' => $this->amount_paid,
        ]);
        $this->reset(['client_name','phone','client_id','plan_id','start_date','end_date','amount_paid']);
        $this->dispatch('success','subscription saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render(PlanService $planService)
    {
        $plans = $planService->getAll();
        return view('livewire.subscriptions.subscription-create',compact('plans'));
    }
}
