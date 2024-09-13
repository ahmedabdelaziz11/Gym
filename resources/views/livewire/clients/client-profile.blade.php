<div wire:ignore.self class="modal modal-lg fade" id="UserProfileModal" tabindex="-1" aria-labelledby="UserProfileModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @if ($client != null)
                <div class="modal-header">
                    <h5 class="modal-title" id="UserProfileModalLabel">
                        {{ $client->name }}
                        <span class="badge bg-secondary">#{{ $client->id }}</span>
                        <span class="badge bg-success">{{ $client->client_type }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        @if ($client->latestSubscription)
                            <div class="card shadow">
                                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                    <h3 class="mb-0">Current Subscription: {{ $client->latestSubscription->plan->name }}</h3>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">
                                            <i class="fas fa-calendar-alt"></i> {{ $client->latestSubscription->start_date }}
                                        </span>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-calendar-alt"></i> {{ $client->latestSubscription->end_date }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <h3 class="mb-0">Last Answer Calls</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>date</th>
                                            <th>type</th>
                                            <th>details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($client->AnswerCalls()->limit(3)->get() as $call)
                                            <tr>
                                                <td>{{ $call->date }}</td>
                                                <td>{{ $call->type }}</td>
                                                <td>{{ $call->comment }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="3">No data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            @endif
        </div>
    </div>
</div>
