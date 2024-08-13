<?php

namespace App\Livewire\Branches;

use App\Services\Dashboard\BranchService;
use Livewire\Component;

class BranchCreate extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255|unique:branches,name',
    ];

    public function save(BranchService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('branch-edit')) {
            abort(403, 'Unauthorized');
        }
        $this->validate();
        $service->create([
            'name' => $this->name,
        ]);
        $this->reset(['name']);
        $this->dispatch('success','Branch saved successfully!'); 
        $this->dispatch('refreshBranchList'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render()
    {
        return view('livewire.branches.branch-create');
    }
}
