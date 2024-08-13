<?php

namespace App\Livewire\Branches;

use App\Services\Dashboard\BranchService;
use Livewire\Component;

class BranchEdit extends Component
{
    public $branchId, $name;

    protected $listeners = ['editBranch'];

    public function editBranch($id,BranchService $service)
    {
        $branch = $service->getById($id);
        $this->branchId = $branch->id;
        $this->name = $branch->name;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:branches,name,' . $this->branchId,
        ];
    }

    public function update(BranchService $service)
    {
        $this->validate($this->rules());
        if (auth()->user() && !auth()->user()->hasPermissionTo('branch-edit')) {
            abort(403, 'Unauthorized');
        }
        $service->update([
            'id' => $this->branchId,
            'name' => $this->name,
        ]);
        $this->dispatch('success','Branch Updated successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshBranchList'); 
        $this->reset();
    }

    public function render()
    {
        return view('livewire.branches.branch-edit');
    }
}
