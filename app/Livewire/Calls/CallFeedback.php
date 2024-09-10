<?php

namespace App\Livewire\Calls;

use App\Enums\ClientStatus;
use App\Enums\ClientType;
use App\Services\Dashboard\CallService;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class CallFeedback extends Component
{
    public $type = '';
    public $call_id = '';
    public $client_type = '';
    public $status = '';
    public $comment = '';
    public $selected_lead_status = '';
    public $next_call_date = '';
    public $lead_status = [];

    protected $listeners = ['callFeedback'];

    protected function rules()
    {
        $commonRules = [
            'call_id' => 'required|exists:calls,id',
            'status' => 'required|string',
            'comment' => 'nullable|string|required_if:status,ANSWER',
        ];
    
        if ($this->client_type == ClientType::LEAD) {
            $leadSpecificRules = [
                'selected_lead_status' => ['nullable', new Enum(ClientStatus::class), 'required_if:status,ANSWER'],
                'next_call_date' => [
                    'nullable',
                    'date',
                    'required_if:selected_lead_status,' . implode(',', [
                        ClientStatus::INTERESTED->value,
                        ClientStatus::QUALIFIED->value,
                        ClientStatus::PENDING->value
                    ])
                ],
            ];
    
            return array_merge($commonRules, $leadSpecificRules);
        }
    
        return $commonRules;
    }

    public function callFeedback($id)
    {
        $this->call_id = $id;
        $callService = new CallService();
        $call = $callService->getById($id);
        $this->client_type = $call->client->client_type;
    }

    public function save(CallService $service)
    {
        $this->validate();

        $service->saveCallFeedback([
            'call_id' => $this->call_id,
            'status'  => $this->status,
            'comment' => $this->comment,
            'selected_lead_status' => $this->selected_lead_status,
            'next_call_date'       => $this->next_call_date,
        ]);

        $this->reset(['call_id','status','comment','next_call_date','selected_lead_status']);
        $this->dispatch('success','Call Feedback Saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshCallList'); 
    }

    public function render()
    {
        $this->lead_status = ClientStatus::cases();
        return view('livewire.calls.call-feedback');
    }
}
