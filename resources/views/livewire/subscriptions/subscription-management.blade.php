<div class="card shadow">
    <div class="card-header border-0">
        <h3 class="mb-0">Subscription Management</h3>
            <div class="float-end">
                @can('subscription-create')
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal"> Create New subscription</button>
                @endcan
            </div>
    </div>
    <div class="card-header border-0">
        <div class="row">
            <div class="col-12 col-md-12">
                <input type="text" wire:model.live.debounce.1000ms="search" name="search" class="form-control mb-3" placeholder="Search Subscriptions...">
            </div>
        </div>            
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th>CLIENT</th>
                    <th>PLAN</th>
                    <th>FROM</th>
                    <th>TO</th>
                    <th>BRANCH</th>
                    <th width="150px">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->client->name }} - {{ $subscription->client->code }}</td>
                    <td>{{ $subscription->plan->name }}</td>
                    <td>{{ $subscription->start_date }}</td>
                    <td>{{ $subscription->end_date }}</td>
                    <td>{{ $subscription->branch->name }}</td>
                    <td>
                        @can('subscription-edit')
                        <button class="btn btn-sm btn-info" wire:click="$dispatch('editSubscription', { id: {{ $subscription->id }} })" data-bs-toggle="modal" data-bs-target="#editSubscriptionModal">Edit</button>
                        @endcan
                        @can('subscription-delete')
                            <button class="btn btn-sm btn-danger" wire:click="deleteSubscription({{ $subscription->id }})">Delete</button>
                        @endcan
                    </td>
                </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="4">No data</td>
                    </tr>
                @endforelse
            </tbody>
            {{ $subscriptions->links('pagination-links') }}
        </table>
    </div>
</div>

