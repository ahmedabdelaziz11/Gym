<?php

namespace App\Services\Dashboard;

use App\Enums\ClientStatus;
use App\Events\LeadInterestedAfterFirstVisit;
use App\Models\Client;

class LeadService 
{
    public function index(array $data)
    {
        $user = auth()->user();
        $search    = isset($data['search'])    ? $data['search'] : null;
        $status    = isset($data['status'])    ? $data['status'] : null;
        $data_type = isset($data['data_type']) ? $data['data_type'] : null;

        $clientsQuery = Client::query()
            ->whereIn('branch_id', $user->branches->pluck('id')->toArray())
            ->lead()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->when($data_type,function($q)use($data_type){
                return $q->where('data_type',$data_type);
            })
            ->when($status,function($q)use($status){
                return $q->where('client_status',$status);
            });
        
        if ($user->hasRole('sales')) {
            $clientsQuery->where('user_id', $user->id);
        }
        return $clientsQuery->orderByDesc('id')->paginate(3);
    }

    public function getAll()
    {
        return Client::query()
            ->lead()
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data)
    {
        return Client::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'national_id' => $data['national_id'],
            'data_type' => $data['data_type'],
            'user_id' => $data['user_id'],
            'client_status' => ClientStatus::PENDING,
            'branch_id'  => auth()->user()->branches->first()->id,
        ]);
    }

    public function saveVisitFeedback(array $data)
    {
        $client = $this->getById($data['lead_id']);
        if($client->user_id != auth()->user()->id)
        {
            abort(403, 'Unauthorized');
        }
        $client->update([
            'visit_comment'  => $data['visit_comment'],
            'client_goal'    => $data['client_goal'],
            'client_status'  => $data['selected_lead_status'],
        ]);
        event(new LeadInterestedAfterFirstVisit($client, $data['next_call_date']));
    }

    public function getById(int $id)
    {
        return Client::lead()
            ->findOrFail($id);
    }

    public function getByPhone(string $phone)
    {
        return Client::where('phone',$phone)
        ->first();
    }

    public function update(array $data): bool
    {
        $client = Client::lead()
        ->findOrFail($data['id']);

        $client->update([
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
        $client->services()->sync($servicesWithCounts);
        return true;
    }

    public function delete($id): bool
    {
        return Client::lead()
            ->findOrFail($id)
            ->delete();
    }
}
