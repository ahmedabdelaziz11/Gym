<?php

namespace App\Services\Dashboard;

use App\Constants\CallStatus;
use App\Constants\CallTypes;
use App\Constants\ClientStatus;
use App\Models\Call;
use App\Models\Client;

class ClientService
{
    public function index(string $searchTerm = null,$dataType = null)
    {
        $user = auth()->user();

        $clientsQuery = Client::query()
            ->whereHas('Showable', function ($q) use ($user) {
                $q->whereIn('branch_id', $user->branches->pluck('id')->toArray());
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            })
            ->when($dataType,function($q)use($dataType){
                return $q->where('data_type',$dataType);
            });
        
        if ($user->hasRole('sales')) {
            $clientsQuery->where('user_id', $user->id);
        }
        
        return $clientsQuery->orderByDesc('id')->paginate(10);
        
    }

    public function getAll()
    {
        return Client::query()
            ->whereHas('Showable', function ($q) {
                $q->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
            })
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data)
    {
        $client = Client::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'national_id' => $data['national_id'],
            'data_type' => $data['data_type'],
            'user_id' => $data['user_id'],
            'client_status' => ClientStatus::INTERESTED
        ]);
        $client->createOrUpdateShowables(auth()->user()->branches->first()->id);
    }

    public function saveVisitFeedback(array $data)
    {
        $client = client::find($data['client_id']);
        if($client->user_id != auth()->user()->id)
        {
            abort(403, 'Unauthorized');
        }
        $client->update([
            'visit_comment' => $data['visit_comment'],
            'next_call_date' => $data['next_call_date'],
            'client_goal' => $data['client_goal'],
        ]);
        $callService = new CallService();
        $callService->create([
            'client_id' => $client->id,
            'Type'      => CallTypes::FIRST_CALL,
            'date'      => $data['next_call_date'],
        ]);
    }

    public function getById(int $id)
    {
        return Client::whereHas('Showable', function ($q) {
            $q->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
        })
            ->findOrFail($id);
    }

    public function update(array $data): bool
    {
        $client = Client::whereHas('Showable', function ($q) {
            $q->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
        })
        ->findOrFail($data['id']);

        $client->update([
            'name' => $data['name'],
            'cost' => $data['cost'],
            'days' => $data['days'],
            'expired_at' => $data['expired_at'],
            'member_count' => $data['member_count'],
        ]);
        $servicesWithCounts = [];
        foreach ($data['services'] as $selectedService) {
            $servicesWithCounts[$selectedService['id']] = ['count' => $selectedService['count']];
        }
        $client->services()->sync($servicesWithCounts);
        $client->createOrUpdateShowables($data['branch_id']);
        return true;
    }

    public function delete($id): bool
    {
        return Client::whereHas('Showable', function ($q) {
            $q->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
        })
            ->find($id)
            ->delete();
    }
}
