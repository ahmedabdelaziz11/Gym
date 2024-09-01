<?php
namespace App\Services\Dashboard;

use App\Models\Service;

class ServiceTypeService 
{
    public function index(string $searchTerm = null)
    {
        return Service::query()
            ->whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray())
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getAll(string $searchTerm = null)
    {
        return Service::query()
            ->whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray())
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data)
    {
        return Service::create([
            'name' => $data['name'],
            'cost' => $data['cost'],
            'branch_id' => $data['branch_id'],
        ]);
    }

    public function getById(int $id)
    {
        return Service::whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray())
            ->findOrFail($id);
    }

    public function update(array $data):bool
    {
        $service = Service::whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray())
            ->findOrFail($data['id']);

        $service->update([
            'name' => $data['name'],
            'cost' => $data['cost'],
            'branch_id' => $data['branch_id'],
        ]);
        return true;
    }

    public function delete($id):bool
    {
        return Service::whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray())
            ->find($id)
            ->delete();
    }
}