<?php

namespace App\Livewire\Plans;

use App\Services\Dashboard\BranchService;
use App\Services\Dashboard\PlanService;
use Livewire\Component;

class PlanEdit extends Component
{
    public $planId;
    public $name;
    public $days;
    public $cost;
    public $memberCount;
    public $expiredAt;
    public $branchId;
    public $services = [];


    protected $listeners = ['editPlan'];

    public function editPlan($id,PlanService $service)
    {
        $plan = $service->getById($id);
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->cost = $plan->cost;
        $this->days = $plan->days;
        $this->memberCount = $plan->member_count;
        $this->expiredAt = $plan->expired_at;
        $this->branchId = $plan->showable->branch_id;
        $this->services = $plan->services()->pluck('service_id')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Plan::where('name', $value)
                        ->whereHas('showable', function($query) {
                            $query->where('branch_id', $this->branch_id);
                        })->exists();
    
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
            'branches' => 'array',
            'services.*' => ['numeric', 'exists:plans,id'],
        ];
    }

    public function update(PlanService $service)
    {
        $this->validate($this->rules());
        if (auth()->user() && !auth()->user()->hasPermissionTo('plan-edit')) {
            abort(403, 'Unauthorized');
        }
        $service->update([
            'name' => $this->name,
            'cost' => $this->cost,
            'days' => $this->days,
            'member_count' => $this->member_count,
            'expired_at' => $this->expired_at,
            'services' => $this->services,
            'branch_id' => $this->branch_id,
        ]);
        $this->dispatch('success','Plan Updated successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshBranchList'); 
        $this->reset();
    }

    public function render(BranchService $service)
    {
        $allBranches = $service->getAll();
        return view('livewire.plans.plan-edit',compact('allBranches'));
    }
}
