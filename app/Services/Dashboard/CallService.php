<?php

namespace App\Services\Dashboard;

use App\Enums\ClientStatus;
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
        $client_status    = isset($data['client_status'])    ? $data['client_status'] : null;

        $query = Call::query()->when($client_id,function($q) use ($client_id) {
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

        if($client_status ==  'Lead')
        {
            $query->whereHas('client',function($q){
                return $q->lead();
            });
        }

        if($client_status == 'Client')
        {
            $query->whereHas('client',function($q){
                return $q->client();
            });
        }
        
        return $query->orderByDesc('date')->orderByDesc('id')->paginate(30);
    }

    public function dailyCalls()
    {
        return Call::query()
            ->whereNull('status')
            ->whereHas('client', function($q){
                $q->where('user_id',auth()->user()->id);
            })->orderByDesc('date')->orderByDesc('id')->paginate(30);
    }

    public function create(array $data)
    {
        $call = Call::create($data);
    }

    public function getById(int $id)
    {
        return Call::findOrFail($id);
    }

    public function update(array $data): bool
    {
        $call = Call::findOrFail($data['id']);
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
        if(isset($data['selected_lead_status']) && $data['selected_lead_status'] != null)
        {
            $call->client->update([
                'client_status' => $data['selected_lead_status'],
            ]);
            if(!in_array($data['selected_lead_status'],[ClientStatus::INTERESTED,ClientStatus::QUALIFIED])){
                Call::where('client_id',$call->client_id)->where('status',null)->delete();
            }
        }
        if((isset($data['next_call_date']) && $data['next_call_date'] != null) || $data['status']  == 'NOT_ANSWER'){
            $this->create([
                'client_id' => $call->client->id,
                'type'      => $call->type,
                'date'      => $data['status']  == 'NOT_ANSWER' ? now()->addDay() : $data['next_call_date'],
                'branch_id' => auth()->user()->branches->first()->id,
            ]);
        }
    }

    public function delete($id): bool
    {
        return Call::find($id)
            ->delete();
    }
}
