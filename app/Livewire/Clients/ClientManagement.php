<?php

namespace App\Livewire\Clients;

use App\Services\Dashboard\ClientService;
use App\Services\Dashboard\CallService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class ClientManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshClientList','callFeedback','pageNumber'];

    public $client_search = '';

    public $currentPageClients = 1;
    public $currentPageCalls = 1;
    public $page_num = 1;
    

    public $type = '';
    public $from = '';
    public $to = '';
    public $search_by_call_status = '';

    public $call_id = '';
    public $status = '';
    public $comment = '';


    public function pageNumber($number)
    {
        $this->page_num = $number;
    }

    public function render(ClientService $service,CallService $callService)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('client-list')) {
            abort(403, 'Unauthorized');
        }

        $clients = $service->index([
            'search'    => $this->client_search,
        ]);
        
        $calls = $callService->index([
            'from'   => $this->from,
            'to'     => $this->to,
            'status' => $this->search_by_call_status,
            'client_status' => 'Client'
        ]);
        return view('livewire.clients.client-management', ['clients' => $clients,'calls' => $calls,'page_num' => $this->page_num]);
    }

    public function deleteClient($id,ClientService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('client-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Client deleted successfully!'); 
        $this->resetPage();
    }

    public function setPage($url)
    {
        $calls = str_contains($url,'calls');
        $clients = str_contains($url,'clients');
        if($calls)
        {
            $this->page_num = 2;
            $this->currentPageCalls = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageCalls;
            });
        }
        elseif($clients)
        {
            $this->page_num = 1;
            $this->currentPageClients = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageClients;
            });
        }elseif($this->page_num == 1){
            $this->currentPageClients = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageClients;
            });
        }elseif($this->page_num == 2){
            $this->page_num = 2;
            $this->currentPageCalls = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageCalls;
            });
        }
    }

    public function refreshClientList()
    {
        $this->resetPage();
    }
}
