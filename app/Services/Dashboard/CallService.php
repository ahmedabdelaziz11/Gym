<?php

namespace App\Services\Dashboard;

use App\Constants\CallTypes;
use App\Constants\ClientStatus;
use App\Models\Call;
use Carbon\Carbon;

class CallService
{
    public function index(array $data = null)
    {
        $user      = auth()->user();
        $from      = isset($data['from'])      ? Carbon::parse(date($data['from']))->startOfDay() : null;
        $to        = isset($data['to'])        ? Carbon::parse(date($data['to']))->endOfDay() : null;
        $client_id = isset($data['client_id']) ? $data['client_id'] : null;
        $type      = isset($data['type'])      ? $data['type'] : null;
        $status    = isset($data['status'])    ? $data['status'] : null;

        $query = Call::query() 
            ->whereHas('Showable', function ($q) use ($user) {
                $q->whereIn('branch_id', $user->branches->pluck('id')->toArray());
            })
            ->when($client_id,function($q) use ($client_id) {
                $q->where('client_id',$client_id);
            })
            ->when($from && $to,function($q)use($from,$to){
                $q->whereBetween('date', [$from, $to]);
            })
            ->when($type,function($q)use($type){
                return $q->where('type',$type);
            })
            ->when($status,function($q)use($status){
                return $q->where('status',$status);
            });
        
        if ($user->hasRole('sales')) {
            $query->whereHas('client', function($q)use ($user){
                $q->where('user_id',$user->id);
            });
        }
        
        return $query->orderByDesc('date')->paginate(10);
        
    }

    public function create(array $data)
    {
        $call = Call::create($data);
        $call->createOrUpdateShowables(auth()->user()->branches->first()->id);
    }

    public function update(array $data): bool
    {
        $call = Call::whereHas('Showable', function ($q) {
            $q->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
        })
        ->findOrFail($data['id']);
        $call->update($data);
        return true;
    }

    public function saveVisitFeedback(array $data)
    {
        $call = Call::find($data['call_id']);
        if($call->client->user_id != auth()->user()->id)
        {
            abort(403, 'Unauthorized');
        }
        $call->update([
            'comment' => $data['comment'],
            'status' => $data['status'],
        ]);
        if($data['next_call_date'] == null)
        {
            $call->client->update([
                'client_status' => ClientStatus::NOT_INTERESTED,
            ]);
        }else{
            $this->create([
                'client_id' => $call->client->id,
                'Type'      => CallTypes::FIRST_CALL,
                'date'      => $data['next_call_date'],
            ]);
        }
    }

    public function delete($id): bool
    {
        return Call::whereHas('Showable', function ($q) {
            $q->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray());
        })
            ->find($id)
            ->delete();
    }
}
