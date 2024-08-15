<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Services\Dashboard\BranchService;
use App\Services\Dashboard\PlanService;
use Livewire\Component;

class PlanCreate extends Component
{
    public $name;
    public $days;
    public $cost;
    public $member_count;
    public $expired_at;
    public $branch_id;
    public $services = [];

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

    public function save(PlanService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('plan-edit')) {
            abort(403, 'Unauthorized');
        }
        $this->validate();
        $service->create([
            'name' => $this->name,
            'cost' => $this->cost,
            'days' => $this->days,
            'member_count' => $this->member_count,
            'expired_at' => $this->expired_at,
            'services' => $this->services,
            'branch_id' => $this->branch_id,
        ]);
        $this->reset(['name','cost','days','member_count','expired_at','services','branch_id']);
        $this->dispatch('success','Plan saved successfully!'); 
        $this->dispatch('refreshServiceList'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render(BranchService $service)
    {
        $allBranches = $service->getAll();
        return view('livewire.plans.plan-create',compact('allBranches'));
    }
}
