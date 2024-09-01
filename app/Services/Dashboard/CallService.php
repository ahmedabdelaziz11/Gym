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
        $from      = isset($data['from']) && $data['from'] != '' ? Carbon::parse(date($data['from']))->startOfDay() : null;
        $to        = isset($data['to']) && $data['from'] != '' ? Carbon::parse(date($data['to']))->endOfDay() : null;
        $client_id = isset($data['client_id']) ? $data['client_id'] : null;
        $type      = isset($data['type'])      ? $data['type'] : null;
        $status    = isset($data['status'])    ? $data['status'] : null;

        $query = Call::query()->whereIn('branch_id', $user->branches->pluck('id')->toArray())
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
        
        return $query->orderByDesc('date')->orderByDesc('id')->paginate(30);
    }

    public function create(array $data)
    {
        $call = Call::create($data);
    }

    public function update(array $data): bool
    {
        $call = Call::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
        ->findOrFail($data['id']);
        $call->update($data);
        return true;
    }

    public function saveCallFeedback(array $data)
    {
        $call = Call::find($data['call_id']);
        if($call->client->user_id != auth()->user()->id)
        {
            abort(403, 'Unauthorized');
        }
        $call->update([
            'comment' => $data['status'] == 'ANSWER' ? $data['comment'] : null,
            'status' => $data['status'],
        ]);
        if($data['selected_lead_status'] != null)
        {
            $call->client->update([
                'client_status' => $data['selected_lead_status'],
            ]);
            if(!in_array($data['selected_lead_status'],[ClientStatus::INTERESTED,ClientStatus::QUALIFED])){
                Call::where('client_id',$call->client_id)->where('status',null)->delete();
            }
        }
        if($data['next_call_date'] != null || $data['status']  == 'NOT_ANSWER'){
            $this->create([
                'client_id' => $call->client->id,
                'Type'      => CallTypes::FIRST_CALL,
                'date'      => $data['status']  == 'NOT_ANSWER' ? now()->addDay() : $data['next_call_date'],
                'branch_id' => auth()->user()->branches->first()->id,
            ]);
        }
    }

    public function delete($id): bool
    {
        return Call::whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
            ->find($id)
            ->delete();
    }
}
