<div>
    <div class="row mb-4">
        <div class="col-lg-12">
            <h2>Plan Management
                @can('plan-list')
                    <div class="float-end">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createPlanModal"> Create New Plan</button>
                    </div>
                @endcan
            </h2>
        </div>
    </div>

    <input type="text" wire:model.live.debounce.1000ms="search" name="search"  class="form-control mb-4" placeholder="Search Plans...">

    <table class="table table-striped table-hover">
        <tr>
            <th>NAME</th>
            <th>DAYS</th>
            <th>COST</th>
            <th>MEMBER COUNT</th>
            <th>EXPIRED AT</th>
            <th>SERVICES</th>
            <th>BRANCH</th>
            <th width="150px">ACTIONS</th>
        </tr>
        @forelse ($plans as $plan)
            <tr>
                <td>{{ $plan->name }}</td>
                <td>{{ $plan->days }}</td>
                <td>{{ number_format($plan->cost,2) }}</td>
                <td>{{ $plan->member_count ?? 'UNLIMITED'	 }}</td>
                <td>{{ $plan->expired_at ?? 'UNLIMITED'	 }}</td>
                <td>
                    @forelse ($plan->services as $service)
                        <div>{{ $service->name }} ({{ $service->pivot->count }})</div>
                    @empty
                        <div>No services</div>
                    @endforelse
                </td>
                <td>{{ $plan->showable->branch->name }}</td>
                <td>
                    @can('plan-edit')
                    <button class="btn btn-sm btn-info" wire:click="$dispatch('editPlan', { id: {{ $plan->id }} })" data-bs-toggle="modal" data-bs-target="#editPlanModal">Edit</button>
                    @endcan
                    @can('plan-delete')
                        <button class="btn btn-sm btn-danger" wire:click="deletePlan({{ $plan->id }})">Delete</button>
                    @endcan
                </td>
            </tr>
        @empty
        <tr>
            <td class="text-center" colspan="8">No data</td>
        </tr>
        @endforelse
    </table>

    {{ $plans->links('pagination-links') }}

    @livewire('plans.plan-create')
    @livewire('plans.plan-edit')
</div>
