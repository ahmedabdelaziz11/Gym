<?php

namespace App\Livewire\Leads;

use App\Constants\ClientDataType;
use App\Constants\ClientStatus;
use App\Services\Dashboard\LeadService;
use App\Services\Dashboard\CallService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;
use ReflectionClass;

class LeadManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshLeadList','editVisitFeedback','callFeedback','pageNumber'];

    public $lead_search = '';
    public $search_by_data_type = '';
    public $search_by_lead_status = '';

    public $currentPageLeads = 1;
    public $currentPageCalls = 1;
    public $page_num = 1;
    
    public $lead_id = '';
    public $lead_status = [];
    public $selected_lead_status = '';
    public $visit_comment = '';
    public $next_call_date = '';
    public $client_goal = '';

    public $from = '';
    public $to = '';
    public $search_by_call_status = '';

    public $type = '';
    public $call_id = '';
    public $status = '';
    public $comment = '';

    public function editVisitFeedback($id)
    {
        $this->lead_id = $id;
    }

    public function callFeedback($id)
    {
        $this->call_id = $id;
    }

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
        
        $allDataTypes      = (new ReflectionClass(ClientDataType::class))->getConstants();
        $this->lead_status = (new ReflectionClass(ClientStatus::class))->getConstants();
        return view('livewire.leads.lead-management', ['leads' => $leads,'allDataTypes' => $allDataTypes,'calls' => $calls,'page_num' => $this->page_num]);
    }

    public function deleteLead($id,LeadService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('lead-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Lead deleted successfully!'); 
        $this->resetPage();
    }

    public function saveVisitFeedback(LeadService $service)
    {
        $validatedData = $this->validate([
            'lead_id'        => 'required|exists:clients,id',
            'visit_comment'  => 'required|string',
            'client_goal'    => 'required|string',
            'selected_lead_status'  => 'required|string',
            'next_call_date' => 'nullable|date|required_if:selected_lead_status,'.ClientStatus::INTERESTED.','.ClientStatus::QUALIFED,
        ]);
        $service->saveVisitFeedback($validatedData);
        $this->reset(['lead_id','visit_comment','client_goal','next_call_date','selected_lead_status']);

        $this->dispatch('success','Visit Feedback Saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->resetPage();
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

    public function saveCallFeedback(CallService $service)
    {
        $validatedData = $this->validate([
            'call_id' => 'required|exists:calls,id',
            'status' => 'required|string',
            'comment' => 'nullable|string|required_if:status,ANSWER',
            'selected_lead_status' => 'nullable|string|required_if:status,ANSWER',
            'next_call_date' => 'nullable|date|required_if:selected_lead_status,'.ClientStatus::INTERESTED.','.ClientStatus::QUALIFED,
        ]);
        $service->saveCallFeedback($validatedData);
        $this->dispatch('success','Call Feedback Saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->reset(['call_id','status','comment','next_call_date','selected_lead_status']);
        $this->resetPage();
    }

    public function refreshLeadList()
    {
        $this->resetPage();
    }
}
