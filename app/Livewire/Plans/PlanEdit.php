<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Services\Dashboard\BranchService;
use App\Services\Dashboard\PlanService;
use Livewire\Component;

class PlanEdit extends Component
{
    public $planId;
    public $name;
    public $days;
    public $cost;
    public $member_count;
    public $expired_at;
    public $branch_id;
    public $service_id;
    public $allServices = [];
    public $selectedServices = [];


    protected $listeners = ['editPlan'];

    public function editPlan($id,PlanService $service)
    {
        $plan = $service->getById($id);
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->cost = $plan->cost;
        $this->days = $plan->days;
        $this->member_count = $plan->member_count;
        $this->expired_at = $plan->expired_at;
        $this->branch_id = $plan->branch_id;
        $this->selectedServices = $plan->services->map(function($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'count' => $service->pivot->count,
            ];
        })->toArray();
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Plan::where('name', $value)->where('id', '!=', $this->planId)->where('branch_id', $this->branch_id)->exists();
                    if ($exists) {
                        $fail('The '.$attribute.' has already been taken for this branch.');
                    }
                },
            ],
            'cost' => 'required|numeric|min:1',
            'days' => 'required|numeric|min:1',
            'member_count' => 'nullable|numeric',
            'expired_at' => 'nullable|date',
            'branch_id' => 'required|exists:branches,id',
            'selectedServices' => 'array',
            'selectedServices.*.id' => 'required|numeric|exists:services,id',
            'selectedServices.*.count' => 'required|numeric|min:1',
        ];
    }

    public function addService()
    {
        $service = collect($this->allServices)->firstWhere('id', $this->service_id);

        if ($service) {
            $this->selectedServices[] = [
                'id' => $service['id'],
                'name' => $service['name'],
                'count' => 1,
            ];
        }
    }

    public function removeService($index)
    {
        unset($this->selectedServices[$index]);
        $this->selectedServices = array_values($this->selectedServices);
    }

    public function update(PlanService $service)
    {
        $this->validate($this->rules());
        if (auth()->user() && !auth()->user()->hasPermissionTo('plan-edit')) {
            abort(403, 'Unauthorized');
        }
        $service->update([
            'id' => $this->planId,
            'name' => $this->name,
            'cost' => $this->cost,
            'days' => $this->days,
            'member_count' => $this->member_count,
            'expired_at' => $this->expired_at,
            'services' => $this->selectedServices,
            'branch_id' => $this->branch_id,
        ]);
        $this->dispatch('success','Plan Updated successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshPlanList'); 
        $this->reset();
    }

    public function render(BranchService $service,PlanService $planService)
    {
        $allBranches = $service->getAll();
        $this->allServices = $planService->getAll();
        return view('livewire.plans.plan-edit',compact('allBranches'));
    }
}
