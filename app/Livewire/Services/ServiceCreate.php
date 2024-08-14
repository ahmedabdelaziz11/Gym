<?php

namespace App\Livewire\Services;

use App\Services\Dashboard\BranchService;
use App\Services\Dashboard\ServiceTypeService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ServiceCreate extends Component
{
    public $name;
    public $cost;
    public $branch_id;

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

    public function save(ServiceTypeService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('service-edit')) {
            abort(403, 'Unauthorized');
        }
        $this->validate();
        $service->create([
            'name' => $this->name,
            'cost' => $this->cost,
            'branch_id' => $this->branch_id,
        ]);
        $this->reset(['name','cost','branch_id']);
        $this->dispatch('success','Service saved successfully!'); 
        $this->dispatch('refreshServiceList'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render(BranchService $service)
    {
        $allBranches = $service->getAll();
        return view('livewire.services.service-create',compact('allBranches'));
    }
}
