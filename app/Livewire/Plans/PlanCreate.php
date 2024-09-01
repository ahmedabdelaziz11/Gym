<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Services\Dashboard\BranchService;
use App\Services\Dashboard\PlanService;
use App\Services\Dashboard\ServiceTypeService;
use Livewire\Component;

class PlanCreate extends Component
{
    public $name;
    public $days;
    public $cost;
    public $member_count;
    public $expired_at;
    public $branch_id;
    public $service_id;
    public $allServices = [];
    public $selectedServices = [];

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Plan::where('name', $value)->where('branch_id', $this->branch_id)->exists();
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
            'services' => $this->selectedServices,
            'branch_id' => $this->branch_id,
        ]);
        $this->reset(['name','cost','days','member_count','expired_at','selectedServices','branch_id']);
        $this->dispatch('success','Plan saved successfully!'); 
        $this->dispatch('refreshPlanList'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render(BranchService $service,ServiceTypeService $serviceTypeService)
    {
        $allBranches = $service->getAll();
        $this->allServices = $serviceTypeService->getAll();
        return view('livewire.plans.plan-create',compact('allBranches'));
    }
}
