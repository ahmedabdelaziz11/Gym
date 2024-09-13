<?php

namespace App\Livewire\Clients;

use App\Services\Dashboard\ClientService;
use Livewire\Component;

class ClientProfile extends Component
{
    public $client_id;
    public $client;
    protected $listeners = ['getClient'];

    public function getClient($id)
    {
        $this->client_id = $id;
        $clientService = new ClientService();
        $this->client = $clientService->getById($id);
    }

    public function render()
    {
        return view('livewire.clients.client-profile');
    }
}
