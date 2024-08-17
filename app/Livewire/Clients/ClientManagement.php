<?php

namespace App\Livewire\Clients;

use App\Constants\ClientDataType;
use App\Services\Dashboard\ClientService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use ReflectionClass;

class ClientManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshClientList','editVisitFeedback'];

    public $search = '';
    public $search_by_data_type = '';
    public $currentPage = 1;

    public $client_id = '';
    public $visit_comment = '';
    public $next_call_date = '';
    public $client_goal = '';



    public function editVisitFeedback($id)
    {
        $this->client_id = $id;
    }

    public function render(ClientService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('client-list')) {
            abort(403, 'Unauthorized');
        }
        $clients = $service->index($this->search,$this->search_by_data_type);
        $allDataTypes = (new ReflectionClass(ClientDataType::class))->getConstants();
        return view('livewire.clients.client-management', ['clients' => $clients,'allDataTypes' => $allDataTypes]);
    }

    public function deleteService($id,ClientService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('client-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Client deleted successfully!'); 
        $this->resetPage();
    }

    public function saveVisitFeedback(ClientService $service)
    {
        $validatedData = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'visit_comment' => 'required|string',
            'next_call_date' => 'required|date',
            'client_goal' => 'required|string',
        ]);
        
        $service->saveVisitFeedback($validatedData);

        $this->dispatch('success','Visit Feedback Saved successfully!'); 
        $this->dispatch('closeModal'); 
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

    public function refreshClientList()
    {
        $this->resetPage();
    }
}
