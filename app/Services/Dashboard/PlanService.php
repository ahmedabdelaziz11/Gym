<?php

namespace App\Services\Dashboard;

use App\Models\Plan;

class PlanService
{
    public function index(string $searchTerm = null)
    {
        return Plan::query()->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getAll()
    {
        return Plan::query()
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data)
    {
        $plan = Plan::create([
            'name' => $data['name'],
            'cost' => $data['cost'],
            'days' => $data['days'],
            'expired_at'   => $data['expired_at'],
            'member_count' => $data['member_count'],
            'branch_id'    => $data['branch_id'],
        ]);
        $servicesWithCounts = [];
        foreach ($data['services'] as $selectedService) {
            $servicesWithCounts[$selectedService['id']] = ['count' => $selectedService['count']];
        }
        $plan->services()->sync($servicesWithCounts);
    }

    public function getById(int $id)
    {
        return Plan::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())->findOrFail($id);
    }

    public function update(array $data): bool
    {
        $plan = Plan::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
        ->findOrFail($data['id']);

        $plan->update([
            'name' => $data['name'],
            'cost' => $data['cost'],
            'days' => $data['days'],
            'expired_at'   => $data['expired_at'],
            'member_count' => $data['member_count'],
            'branch_id'    => $data['branch_id'],
        ]);
        $servicesWithCounts = [];
        foreach ($data['services'] as $selectedService) {
            $servicesWithCounts[$selectedService['id']] = ['count' => $selectedService['count']];
        }
        $plan->services()->sync($servicesWithCounts);
        return true;
    }

    public function delete($id): bool
    {
        return Plan::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->find($id)
            ->delete();
    }
}
