<?php

namespace App\Livewire\Clients;

use App\Constants\ClientDataType;
use App\Models\Client;
use App\Services\Dashboard\ClientService;
use App\Services\Dashboard\UserService;
use Livewire\Component;
use ReflectionClass;

class ClientCreate extends Component
{
    public $name;
    public $phone;
    public $email;
    public $national_id;
    public $data_type;
    public $user_id;
    public $allSellers = [];
    public $allDataTypes = [];

    protected function rules()
    {
        return [
            'phone' => [
                'required',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/',
                '',
                function ($attribute, $value, $fail) {
                    $exists = Client::where('phone', $value)
                        ->whereHas('showable', function($query) {
                            $query->where('branch_id', auth()->user()->branches->first()->id);
                        })->exists();
    
                    if ($exists) {
                        $fail('The '.$attribute.' has already been taken for this branch.');
                    }
                },
            ],
            'name' => 'required|max:255|string',
            'email' => 'nullable|email',
            'national_id' => 'nullable|regex:/^[2-3]{1}[0-9]{13}$/',
            'data_type' => 'required',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function save(ClientService $service)
    {
        if (auth()->user() && !auth()->user()->hasPermissionTo('client-create')) {
            abort(403, 'Unauthorized');
        }
        $this->validate();
        $service->create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'national_id' => $this->national_id,
            'data_type' => $this->data_type,
            'user_id' => $this->user_id,
        ]);
        $this->reset(['name','phone','email','national_id','user_id']);
        $this->dispatch('success','Client saved successfully!'); 
        $this->dispatch('refreshClientList'); 
        $this->dispatch('closeModal'); 
        $this->reset();
    }

    public function render(UserService $userService)
    {
        $this->allDataTypes = (new ReflectionClass(ClientDataType::class))->getConstants();
        $this->allSellers = $userService->getSeller();
        return view('livewire.clients.client-create');
    }
}
