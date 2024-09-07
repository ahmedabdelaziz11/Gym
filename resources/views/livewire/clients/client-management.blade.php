

<div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button wire:click="$dispatch('pageNumber', { number: 1 })" class="nav-link @if($page_num == 1) active @endif" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Clients</button>
            <button wire:click="$dispatch('pageNumber', { number: 2 })" class="nav-link @if($page_num == 2) active @endif" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Calls</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade @if($page_num == 1) show active @endif" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Client Management</h3>
                        <div class="float-end">
                            @can('subscription-create')
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal"> Create New subscription</button>
                            @endcan
                        </div>
                </div>
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <input type="text" wire:model.live.debounce.1000ms="client_search" name="client_search" class="form-control mb-3" placeholder="Search By Name or Phone...">
                        </div>
                    </div>            
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">NAME</th>
                                <th scope="col">PHONE</th>
                                <th scope="col">SELLER</th>
                                <th scope="col">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clients as $client)
                            <tr>
                                <td>{{$client->name}}</td>
                                <td>{{$client->phone}}</td>
                                <td>{{$client->seller->name}}</td>
                                <td>

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
            </div>
        </div>

        <div class="tab-pane fade  @if($page_num == 2) show active @endif" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Call Management</h3>
                </div>
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <select wire:model.change="search_by_call_status" class="form-select" id="search_by_call_status">
                                <option value="">Select Status</option>
                                <option value="ANSWER">ANSWER</option>
                                <option value="NOT_ANSWER">NOT_ANSWER</option>
                                <option value="null">NO ACTION TAKEN</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <input type="date" wire:model.change="from" name="from" class="form-control mb-3">
                        </div>
                        <div class="col-12 col-md-4">
                            <input type="date" wire:model.change="to" name="to" class="form-control mb-3">
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
                @include('livewire.calls.client-call-feedback')
            </div>
        </div>
    </div>
</div>

