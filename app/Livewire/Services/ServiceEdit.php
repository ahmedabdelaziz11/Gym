<?php

namespace App\Livewire\Services;

use App\Services\Dashboard\BranchService;
use App\Services\Dashboard\ServiceTypeService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ServiceEdit extends Component
{
    public $serviceId;
    public $name;
    public $cost;
    public $branch_id;

    protected $listeners = ['editService'];

    public function editService($id,ServiceTypeService $service)
    {
        $service = $service->getById($id);
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->cost = $service->cost;
        $this->branch_id = $service->showable->branch_id;
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Service::where('name', $value)
                        ->whereHas('showable', function($query) {
                            $query->where('branch_id', $this->branch_id);
                        })->exists();
    
                    if ($exists) {
                        $fail('The '.$attribute.' has already been taken for this branch.');
                    }
                },
            ],
            'cost' => 'required|numeric|min:1',
            'branch_id' => 'required|exists:branches,id',
        ];
    }

    public function update(ServiceTypeService $service)
    {
        $this->validate($this->rules());
        if (auth()->user() && !auth()->user()->hasPermissionTo('service-edit')) {
            abort(403, 'Unauthorized');
        }
        $service->update([
            'id' => $this->serviceId,
            'name' => $this->name,
            'cost' => $this->cost,
            'branch_id' => $this->branch_id,
        ]);
        $this->dispatch('success','Branch Updated successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshBranchList'); 
        $this->reset();
    }

    public function render(BranchService $service)
    {
        $allBranches = $service->getAll();
        return view('livewire.services.service-edit',compact('allBranches'));
    }
}
