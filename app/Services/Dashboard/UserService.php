<?php
namespace App\Services\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService 
{
    public function index(string $searchTerm = null)
    {
        return User::query()
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('email', 'like', '%' . $searchTerm . '%')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getSeller()
    {
        return User::query()
        ->whereHas('branches', function ($q) {
            $q->where('branch_id', auth()->user()->branches->first()->id);
        })
        ->whereHas('roles', function ($query) {
            $query->where('name', 'sales');
        })
        ->orderByDesc('id')
        ->get();
    }

    public function create(array $data):bool
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles($data['roles']);
        $user->branches()->sync($data['branches']);
        return true;
    }

    public function getById(int $id)
    {
        return User::findOrFail($id);
    }

    public function update(array $data):bool
    {
        $user = User::findOrFail($data['id']);
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->syncRoles($data['roles']);
        $user->branches()->sync($data['branches']);
        return true;
    }

    public function delete($id):bool
    {
        return User::find($id)->delete();
    }
}