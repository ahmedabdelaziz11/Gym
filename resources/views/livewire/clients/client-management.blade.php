<div class="card shadow">
    <div class="card-header border-0">
        <h3 class="mb-0">Client Management</h3>
        @can('client-create')
            <div class="float-end">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createClientModal"> Create New Client</button>
            </div>
        @endcan
    </div>
    <div class="card-header border-0">
        <div class="row">
            <div class="col-12 col-md-8">
                <input type="text" wire:model.live.debounce.1000ms="search" name="search" class="form-control mb-3" placeholder="Search Clients...">
            </div>
            <div class="col-12 col-md-4">
                <select class="form-control" id="data_type" wire:model.change="search_by_data_type">
                    <option value="">Select Data Type</option>
                    @foreach ($allDataTypes as $key => $value )
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>            
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th scope="col">NAME</th>
                    <th scope="col">PHONE</th>
                    <th scope="col">DATA TYPE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">SELLER</th>
                    <th scope="col">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                <tr>
                    <td>{{$client->name}}</td>
                    <td>{{$client->phone}}</td>
                    <td>{{$client->data_type}}</td>
                    <td>{{$client->client_status}}</td>
                    <td>{{$client->seller->name}}</td>
                    <td>
                        @if (auth()->user()->id == $client->user_id && $client->visit_comment == null)
                            <button class="btn btn-sm btn-danger" wire:click="$dispatch('editVisitFeedback', { id: {{ $client->id }} })" data-bs-toggle="modal" data-bs-target="#VisitFeedBackModal">Visit Feadback</button>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="8">No data</td>
                    </tr>
                @endforelse
            </tbody>
            {{ $clients->links('pagination-links') }}
        </table>
    </div>
    @livewire('clients.client-create')
    @include('livewire.clients.visit-feedback')
    {{-- @livewire('clients.client-edit') --}}
</div>


