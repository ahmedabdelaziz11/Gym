<?php
namespace App\Services\Dashboard;

use App\Models\Service;

class ServiceTypeService 
{
    public function index(string $searchTerm = null)
    {
        return Service::query()
            ->whereHas('Showable', function($q){
                $q->whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray());
            })
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function create(array $data)
    {
        $service = Service::create([
            'name' => $data['name'],
            'cost' => $data['cost']
        ]);
        $service->createOrUpdateShowables($data['branch_id']);
    }

    public function getById(int $id)
    {
        return Service::whereHas('Showable', function($q){
                $q->whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray());
            })
            ->findOrFail($id);
    }

    public function update(array $data):bool
    {
        $service = Service::whereHas('Showable', function($q){
                $q->whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray());
            })
            ->findOrFail($data['id']);

        $service->update([
            'name' => $data['name'],
            'cost' => $data['cost']
        ]);
        $service->createOrUpdateShowables($data['branch_id']);
        return true;
    }

    public function delete($id):bool
    {
        return Service::whereHas('Showable', function($q){
                $q->whereIn('branch_id',auth()->user()->branches->pluck('id')->toArray());
            })
            ->find($id)
            ->delete();
    }
}