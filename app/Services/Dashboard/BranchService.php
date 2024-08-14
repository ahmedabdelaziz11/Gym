<?php
namespace App\Services\Dashboard;

use App\Models\Branch;

class BranchService 
{
    public function index(string $searchTerm = null)
    {
        return Branch::query()
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getAll()
    {
        return Branch::query()
            ->whereIn('id',auth()->user()->branches->pluck('id')->toArray())
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data)
    {
        return Branch::create([
            'name' => $data['name'],
        ]);
    }

    public function getById(int $id)
    {
        return Branch::findOrFail($id);
    }

    public function update(array $data):bool
    {
        $branch = Branch::findOrFail($data['id']);
        $branch->update([
            'name' => $data['name'],
        ]);
        return true;
    }

    public function delete($id):bool
    {
        return Branch::find($id)->delete();
    }
}