<div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button wire:click="$dispatch('pageNumber', { number: 1 })"
                class="nav-link @if ($page_num == 1) active @endif" id="nav-home-tab" data-bs-toggle="tab"
                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                aria-selected="true">Leads</button>
            <button wire:click="$dispatch('pageNumber', { number: 2 })"
                class="nav-link @if ($page_num == 2) active @endif" id="nav-profile-tab"
                data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab"
                aria-controls="nav-profile" aria-selected="false">Calls</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade @if ($page_num == 1) show active @endif" id="nav-home" role="tabpanel"
            aria-labelledby="nav-home-tab" tabindex="0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Lead Management</h3>
                </div>
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <input type="text" wire:model.live.debounce.1000ms="lead_search" name="lead_search"
                                class="form-control mb-3" placeholder="Search By Name or Phone...">
                        </div>
                        <div class="col-12 col-md-3">
                            <select class="form-control" id="data_type" wire:model.change="search_by_data_type">
                                <option value="">Select Data Type</option>
                                @foreach ($allDataTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select class="form-control" id="search_by_lead_status"
                                wire:model.change="search_by_lead_status">
                                <option value="">Select Status</option>
                                @foreach ($lead_status as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
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
                            @forelse ($leads as $lead)
                                <tr>
                                    <td>{{ $lead->name }}</td>
                                    <td>{{ $lead->phone }}</td>
                                    <td>{{ $lead->data_type }}</td>
                                    <td>{{ $lead->client_status }}</td>
                                    <td>{{ $lead->seller->name }}</td>
                                    <td>
                                        @if (auth()->user()->id == $lead->user_id && $lead->visit_comment == null)
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="$dispatch('editVisitFeedback', { id: {{ $lead->id }} })"
                                                data-bs-toggle="modal" data-bs-target="#VisitFeedBackModal">
                                                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    width="18" height="18" color="#fff7f5" fill="none">
                                                    <path d="M14 6H22M18 2L18 10" stroke="currentColor"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M6.09881 19.5C4.7987 19.3721 3.82475 18.9816 3.17157 18.3284C2 17.1569 2 15.2712 2 11.5V11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3H11.5M6.5 18C6.29454 19.0019 5.37769 21.1665 6.31569 21.8651C6.806 22.2218 7.58729 21.8408 9.14987 21.0789C10.2465 20.5441 11.3562 19.9309 12.5546 19.655C12.9931 19.5551 13.4395 19.5125 14 19.5C17.7712 19.5 19.6569 19.5 20.8284 18.3284C21.947 17.2098 21.9976 15.4403 21.9999 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <path d="M8 14H14M8 9H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>Visit Feadback
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center m-2">
                    {{ $leads->links('pagination-links') }}
                </div>
                @livewire('leads.visit-feedback')
            </div>
        </div>

        <div class="tab-pane fade @if ($page_num == 2) show active @endif" id="nav-profile"
            role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Call Management</h3>
                </div>
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <select wire:model.change="search_by_call_status" class="form-select"
                                id="search_by_call_status">
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
                                    <td>{{ $call->client->name }}</td>
                                    <td>{{ $call->date }}</td>
                                    <td>{{ $call->type }}</td>
                                    <td>{{ $call->status }}</td>
                                    <td>{{ $call->comment }}</td>
                                    <td>
                                        @if (auth()->user()->id == $call->client->user_id && $call->status == null)
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="$dispatch('callFeedback', { id: {{ $call->id }} })"
                                                data-bs-toggle="modal" data-bs-target="#CallFeedBackModal">Call
                                                Feadback</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="7">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center m-2">
                    {{ $calls->links('pagination-links') }}
                </div>
                @livewire('calls.call-feedback')
            </div>
        </div>
    </div>
</div>
