<?php

namespace App\Livewire\Leads;

use App\Enums\ClientDataType;
use App\Enums\ClientStatus;
use App\Services\Dashboard\LeadService;
use App\Services\Dashboard\CallService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class LeadManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshLeadList','refreshCallList','pageNumber'];

    public $lead_search = '';
    public $search_by_data_type = '';
    public $search_by_lead_status = '';
    public $lead_status = [];


    public $currentPageLeads = 1;
    public $currentPageCalls = 1;
    public $page_num = 1;
    

    public $from = '';
    public $to = '';
    public $search_by_call_status = '';



    public function pageNumber($number)
    {
        $this->page_num = $number;
    }

    public function render(LeadService $service,CallService $callService)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('lead-list')) {
            abort(403, 'Unauthorized');
        }

        $leads = $service->index([
            'search'    => $this->lead_search,
            'data_type' => $this->search_by_data_type,
            'status'    => $this->search_by_lead_status
        ]);
        
        $calls = $callService->index([
            'from'   => $this->from,
            'to'     => $this->to,
            'status' => $this->search_by_call_status,
            'client_status' => 'Lead'
        ]);
        
        $allDataTypes      = ClientDataType::cases();
        $this->lead_status = ClientStatus::cases();
        return view('livewire.leads.lead-management', ['leads' => $leads,'allDataTypes' => $allDataTypes,'calls' => $calls,'page_num' => $this->page_num]);
    }

    public function setPage($url)
    {
        $calls = str_contains($url,'calls');
        $leads = str_contains($url,'leads');
        if($calls)
        {
            $this->page_num = 2;
            $this->currentPageCalls = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageCalls;
            });
        }
        elseif($leads)
        {
            $this->page_num = 1;
            $this->currentPageLeads = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageLeads;
            });
        }elseif($this->page_num == 1){
            $this->currentPageLeads = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageLeads;
            });
        }elseif($this->page_num == 2){
            $this->page_num = 2;
            $this->currentPageCalls = explode('page=', $url)[1] ?? 1;
            Paginator::currentPageResolver(function(){
                return $this->currentPageCalls;
            });
        }
    }

    public function refreshLeadList()
    {
        $this->resetPage();
    }

    public function refreshCallList()
    {
        $this->resetPage();
    }
}
