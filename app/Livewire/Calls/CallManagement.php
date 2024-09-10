<?php

namespace App\Livewire\Calls;

use App\Services\Dashboard\CallService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class CallManagement extends Component
{
    use WithPagination;

    public $currentPage = 1;
    protected $listeners = ['refreshCallList'];


    public function render(CallService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('call-list')) {
            abort(403, 'Unauthorized');
        }
        $calls = $service->dailyCalls();
        return view('livewire.calls.call-management', ['calls' => $calls]);
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
