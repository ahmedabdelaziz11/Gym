<?php

namespace App\Livewire\Subscriptions;

use App\Services\Dashboard\LeadService;
use App\Services\Dashboard\PlanService;
use App\Services\Dashboard\SubscriptionService;
use Livewire\Component;

class SubscriptionCreate extends Component
{
    public $phone;
    public $client_id;
    public $client_name;
    public $client_code;
    public $last_plan_name;
    public $last_plan_expired_at;

    public $plan_id;
    public $start_date;
    public $end_date;
    public $plan_name;
    public $plan_days;
    public $plan_services = [];
    public $amount_paid;

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
    }

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

    public function getUser(LeadService $leadService)
    {
        $client = $leadService->getByPhone($this->phone);
        if ($client) {
            $this->client_id = $client->id;
            $this->client_name = $client->name;
            $this->client_code = $client->id;
            $this->last_plan_name = $client->latestSubscription->plan->name;
            $this->last_plan_expired_at = $client->latestSubscription->end_date;
        } else {
            $this->reset(['client_id','client_name','client_code']);

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
        $plan = $value != null ? $planService->getById($value) : null;
    
        if ($plan) {
            $this->plan_id = $plan->id;
            $this->plan_name = $plan->name;
            $this->plan_days = $plan->days;
            $this->amount_paid = $plan->cost;
            $this->plan_services = $plan->services;
            $this->end_date = \Carbon\Carbon::parse($this->start_date)->addDays($plan->days)->format('Y-m-d');
        } else {
            $this->reset(['plan_id','plan_name','plan_days','amount_paid','plan_services']);
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
