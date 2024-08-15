<?php

namespace App\Livewire\Plans;

use App\Services\Dashboard\PlanService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class PlanManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshPlanList' => 'refreshPlanList'];

    public $search = '';
    public $currentPage = 1;

    public function render(PlanService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('plan-list')) {
            abort(403, 'Unauthorized');
        }
        $plans = $service->index($this->search);
        return view('livewire.plans.plan-management', ['plans' => $plans]);
    }

    public function deleteService($id,PlanService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('plan-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Plan deleted successfully!'); 
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

    public function refreshPlanList()
    {
        $this->resetPage();
    }
}
