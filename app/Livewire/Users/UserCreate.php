<?php

namespace App\Livewire\Users;

use App\Models\Branch;
use App\Services\Dashboard\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    public $name, $email, $password, $roles = [], $branches = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6',
        'roles' => 'required|array',
        'branches' => 'required|array',
        'branches.*' => ['numeric', 'exists:branches,id'],
    ];

    public function save(UserService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('user-create')) {
            abort(403, 'Unauthorized');
        }
        $this->validate();
        $service->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
            'branches' => $this->branches,
        ]);
        $this->reset(['name', 'email', 'password', 'roles', 'branches']);
        $this->dispatch('success','User saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshUserList'); 
        $this->reset();
    }

    public function render()
    {
        return view('livewire.users.user-create', ['allRoles' => Role::all(),'allBranches' => Branch::all()]);
    }
}
