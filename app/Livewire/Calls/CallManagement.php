<?php

namespace App\Livewire\Calls;

use App\Services\Dashboard\CallService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class CallManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshCallList','callFeedback'];

    public $from = '';
    public $to = '';
    public $type = '';
    public $next_call_date = '';
    public $comment = '';
    public $status = '';
    public $client_id = '';
    public $call_id = '';
    public $currentPage = 1;

    public function render(CallService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('call-list')) {
            abort(403, 'Unauthorized');
        }
        $calls = $service->index([
                $this->from,
                $this->type,
                $this->status,
                $this->client_id,
            ]);
        return view('livewire.calls.call-management', ['calls' => $calls]);
    }

    public function callFeedback($id)
    {
        $this->call_id = $id;
    }

    public function deleteService($id,CallService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('call-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Call deleted successfully!'); 
        $this->resetPage();
    }

    public function saveCallFeedback(CallService $service)
    {
        $validatedData = $this->validate([
            'call_id' => 'required|exists:calls,id',
            'status' => 'required|string',
            'comment' => 'nullable|string|required_if:status,ANSWER',
            'next_call_date' => 'nullable|date|required_if:status,NOT_ANSWER',
        ]);
        $service->saveVisitFeedback($validatedData);

        $this->dispatch('success','Call Feedback Saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->reset(['call_id','status','comment','next_call_date']);
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

    public function refreshCallList()
    {
        $this->resetPage();
    }
}
