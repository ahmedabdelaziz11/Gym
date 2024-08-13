<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\Dashboard\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    public $userId, $name, $email,$password, $roles = [];

    protected $listeners = ['editUser'];

    public function editUser($id,UserService $service)
    {
        $user = $service->getById($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = "";
        $this->roles = $user->getRoleNames()->toArray();
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
        ]);
        $this->dispatch('success','User Updated successfully!'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render()
    {
        return view('livewire.users.user-edit', ['allRoles' => Role::all()]);
    }
}
