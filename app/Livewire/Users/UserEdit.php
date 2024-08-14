<?php

namespace App\Livewire\Users;

use App\Models\Branch;
use App\Services\Dashboard\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    public $userId, $name, $email,$password, $roles = [], $branches = [];

    protected $listeners = ['editUser'];

    public function editUser($id,UserService $service)
    {
        $user = $service->getById($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = "";
        $this->roles = $user->getRoleNames()->toArray();
        $this->branches = $user->branches()->pluck('branch_id')->toArray();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'roles' => 'required|array',
            'password' => $this->userId ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }

    public function update(UserService $service)
    {
        $this->validate($this->rules());
        if (auth()->user() && !auth()->user()->hasPermissionTo('user-edit')) {
            abort(403, 'Unauthorized');
        }
        $service->update([
            'id' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
            'branches' => $this->branches,
        ]);
        $this->dispatch('success','User Updated successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshUserList'); 
    }

    public function render()
    {
        return view('livewire.users.user-edit', ['allRoles' => Role::all(),'allBranches' => Branch::all()]);
    }
}
