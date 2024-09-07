<?php

namespace App\Livewire\Subscriptions;

use App\Services\Dashboard\SubscriptionService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class SubscriptionManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshSubscriptionList' => 'refreshSubscriptionList'];

    public $search = '';
    public $currentPage = 1;

    public function render(SubscriptionService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('subscription-list')) {
            abort(403, 'Unauthorized');
        }
        $subscriptions = $service->index(['search_term' => $this->search]);
        return view('livewire.subscriptions.subscription-management', ['subscriptions' => $subscriptions]);
    }

    public function deleteSubscription($id,SubscriptionService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('subscription-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','Subscription deleted successfully!'); 
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

    public function refreshSubscriptionList()
    {
        $this->resetPage();
    }
}
