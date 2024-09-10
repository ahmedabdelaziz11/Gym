<div class="card shadow">
    <div class="card-header border-0">
        <h3 class="mb-0">Daily Calls</h3>
    </div>

    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th scope="col">CLIENT NAME</th>
                    <th scope="col">CLIENT PHONE</th>
                    <th scope="col">DATE</th>
                    <th scope="col">TYPE</th>
                    <th scope="col">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($calls as $call)
                @php
                    $date = \Carbon\Carbon::parse($call->date);
                    $now = \Carbon\Carbon::now();
                @endphp
                    <tr>
                        <td>{{$call->client->name}}</td>
                        <td>{{$call->client->phone}}</td>
                        <td>
                            <p class="{{ $date->diffInDays($now) >= 1 ? 'btn btn-sm btn-danger text-white' : '' }}" style="cursor: default;">{{$call->date}}</p>
                        </td>
                        <td>{{$call->type}}</td>
                        <td>
                            @if (auth()->user()->id == $call->client->user_id && $call->status == null)
                                <button class="btn btn-sm btn-primary" wire:click="$dispatch('callFeedback', { id: {{ $call->id }} })" data-bs-toggle="modal" data-bs-target="#CallFeedBackModal">Call Feadback</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="4">No data</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
        <div class="d-flex justify-content-center m-2">
            {{ $calls->links('pagination-links') }}
        </div>
        @livewire('calls.call-feedback')
    </div>
</div>

