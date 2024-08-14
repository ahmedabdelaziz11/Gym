<?php

namespace App\Livewire\Users;

use App\Services\Dashboard\UserService;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    protected $listeners = ['refreshUserList' => 'refreshUserList'];

    public $search = '';
    public $currentPage = 1;

    public function render(UserService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('user-list')) {
            abort(403, 'Unauthorized');
        }
        $users = $service->index($this->search);
        return view('livewire.users.user-management', ['users' => $users]);
    }

    public function deleteUser($id,UserService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('user-delete')) {
            abort(403, 'Unauthorized');
        }
        $service->delete($id);
        $this->dispatch('success','User deleted successfully!'); 
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

    public function refreshUserList()
    {
        $this->resetPage();
    }
}
