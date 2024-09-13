<?php

namespace App\Services\Dashboard;

use App\Models\Client;

class ClientService
{
    public function index(array $data)
    {
        $user = auth()->user();
        $search    = isset($data['search'])    ? $data['search'] : null;

        $clientsQuery = Client::query()
            ->whereIn('branch_id', $user->branches->pluck('id')->toArray())
            ->client()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        
        if ($user->hasRole('sales')) {
            $clientsQuery->where('user_id', $user->id);
        }
        return $clientsQuery->orderByDesc('id')->paginate(3);
    }

    public function getAll()
    {
        return Client::query()
            ->client()
            ->orderByDesc('id')
            ->get();
    }



    public function getById(int $id)
    {
        return Client::findOrFail($id);
    }

    public function getByPhone(string $phone)
    {
        return Client::client()
        ->where('phone',$phone)
        ->first();
    }

    public function delete($id): bool
    {
        return Client::client()
            ->find($id)
            ->delete();
    }
}
