<?php

namespace App\Livewire\Branches;

use App\Services\Dashboard\BranchService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class BranchManagement extends Component
{
    use WithPagination;

    protected $listeners = ['branchCreated' => 'refreshBranchList'];

    public $search = '';
    public $currentPage = 1;

    public function render(BranchService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('branch-list')) {
            abort(403, 'Unauthorized');
        }
        $branches = $service->index($this->search);
        return view('livewire.branches.branch-management', ['branches' => $branches]);
    }

    public function deleteBranch($id,BranchService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('branch-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Branch deleted successfully!'); 
        $this->resetPage();
    }

    public function setPage($url)
    {
        $urlParts = explode('page=', $url);
        if (isset($urlParts[1])) {
            $this->currentPage = $urlParts[1];
        } else {
            $this->currentPage = 1;
        }
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }

    public function refreshBranchList()
    {
        $this->resetPage();
    }
}
