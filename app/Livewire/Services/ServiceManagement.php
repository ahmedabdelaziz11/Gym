<?php

namespace App\Livewire\Services;

use App\Services\Dashboard\ServiceTypeService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class ServiceManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshServiceList' => 'refreshServiceList'];

    public $search = '';
    public $currentPage = 1;

    public function render(ServiceTypeService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('service-list')) {
            abort(403, 'Unauthorized');
        }
        $services = $service->index($this->search);
        return view('livewire.services.service-management', ['services' => $services]);
    }

    public function deleteService($id,ServiceTypeService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('service-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Service deleted successfully!'); 
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

    public function refreshServiceList()
    {
        $this->resetPage();
    }
}
