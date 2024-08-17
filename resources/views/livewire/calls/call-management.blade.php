<div class="card shadow">
    <div class="card-header border-0">
        <h3 class="mb-0">Call Management</h3>
        {{-- @can('client-create')
            <div class="float-end">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createCallModal"> Create New Call</button>
            </div>
        @endcan --}}
    </div>
    <div class="card-header border-0">
        <div class="row">
            <div class="col-12 col-md-8">
                <input type="text" wire:model.live.debounce.1000ms="search" name="search" class="form-control mb-3" placeholder="Search Calls...">
            </div>
            <div class="col-12 col-md-4">
            </div>
        </div>            
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th scope="col">CLIENT</th>
                    <th scope="col">DATE</th>
                    <th scope="col">TYPE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">COMMENT</th>
                    <th scope="col">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($calls as $call)
                <tr>
                    <td>{{$call->client->name}}</td>
                    <td>{{$call->date}}</td>
                    <td>{{$call->type}}</td>
                    <td>{{$call->status}}</td>
                    <td>{{$call->comment}}</td>
                    <td>
                        @if (auth()->user()->id == $call->client->user_id && $call->status == null)
                            <button class="btn btn-sm btn-danger" wire:click="$dispatch('callFeedback', { id: {{ $call->id }} })" data-bs-toggle="modal" data-bs-target="#CallFeedBackModal">Call Feadback</button>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="7">No data</td>
                    </tr>
                @endforelse
            </tbody>
            {{ $calls->links('pagination-links') }}
        </table>
    </div>
    {{-- @livewire('clients.client-create') --}}
    @include('livewire.calls.call-feedback')
    {{-- @livewire('clients.client-edit') --}}
</div>


