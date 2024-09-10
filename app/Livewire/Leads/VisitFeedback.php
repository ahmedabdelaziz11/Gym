<?php

namespace App\Livewire\Leads;

use App\Enums\ClientStatus;
use App\Services\Dashboard\LeadService;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class VisitFeedback extends Component
{
    public $lead_id = '';
    public $selected_lead_status = '';
    public $visit_comment = '';
    public $next_call_date = '';
    public $client_goal = '';

    public $lead_status = [];

    protected $listeners = ['editVisitFeedback'];

    protected function rules()
    {
        return [
            'lead_id'        => 'required|exists:clients,id',
            'visit_comment'  => 'required|string',
            'client_goal'    => 'required|string',
            'selected_lead_status'  => ['required', new Enum(ClientStatus::class)],
            'next_call_date' => 'nullable|date|required_if:selected_lead_status,' . ClientStatus::INTERESTED->value . ',' . ClientStatus::QUALIFIED->value . ',' . ClientStatus::PENDING->value,
        ];
    }

    public function editVisitFeedback($id)
    {
        $this->lead_id = $id;
    }

    public function save(LeadService $service)
    {
        $this->validate();
        $service->saveVisitFeedback([
            'lead_id'       => $this->lead_id,
            'visit_comment' => $this->visit_comment,
            'client_goal'   => $this->client_goal,
            'selected_lead_status' => $this->selected_lead_status,
            'next_call_date'       => $this->next_call_date,
        ]);

        $this->reset(['lead_id','visit_comment','client_goal','next_call_date','selected_lead_status']);
        $this->dispatch('success','Visit Feedback Saved successfully!'); 
        $this->dispatch('closeModal'); 
        $this->dispatch('refreshLeadList'); 
    }

    public function render()
    {
        $this->lead_status = ClientStatus::cases();
        return view('livewire.leads.visit-feedback');
    }
}
