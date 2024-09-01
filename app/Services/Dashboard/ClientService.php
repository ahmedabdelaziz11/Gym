<?php

namespace App\Services\Dashboard;

use App\Constants\CallTypes;
use App\Constants\ClientStatus;
use App\Models\Client;

class ClientService
{
    public function index(array $data)
    {
        $user = auth()->user();
        $search    = isset($data['search'])    ? $data['search'] : null;
        $status    = isset($data['status'])    ? $data['status'] : null;
        $data_type = isset($data['data_type']) ? $data['data_type'] : null;

        $clientsQuery = Client::query()
            ->whereIn('branch_id', $user->branches->pluck('id')->toArray())
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
            ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
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
        $client = client::find($data['lead_id']);
        if($client->user_id != auth()->user()->id)
        {
            abort(403, 'Unauthorized');
        }
        $client->update([
            'visit_comment'  => $data['visit_comment'],
            'next_call_date' => $data['next_call_date'] != '' ? $data['next_call_date']  : null,
            'client_goal'    => $data['client_goal'],
            'client_status'    => $data['selected_lead_status'],
        ]);
        if($data['next_call_date']){
            $callService = new CallService();
            $callService->create([
                'client_id' => $client->id,
                'Type'      => CallTypes::FIRST_CALL,
                'date'      => $data['next_call_date'],
                'branch_id' => auth()->user()->branches->first()->id
            ]);
        }
    }

    public function getById(int $id)
    {
        return Client::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())->findOrFail($id);
    }

    public function update(array $data): bool
    {
        $client = Client::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())->findOrFail($data['id']);

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
        return Client::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->find($id)
            ->delete();
    }
}
