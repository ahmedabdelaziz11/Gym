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
            <th>Name</th>
            <th>Cost</th>
            <th>Branch</th>
            <th width="280px">Action</th>
        </tr>
        @forelse ($plans as $plan)
            <tr>
                <td>{{ $plan->name }}</td>
                <td>{{ number_format($plan->cost,2) }}</td>
                <td>{{ $plan->showable->branch->name }}</td>
                <td>
                    @can('plan-edit')
                    <button class="btn btn-info" wire:click="$dispatch('editPlan', { id: {{ $plan->id }} })" data-bs-toggle="modal" data-bs-target="#editPlanModal">Edit</button>
                    @endcan
                    @can('plan-delete')
                        <button class="btn btn-danger" wire:click="deletePlan({{ $plan->id }})">Delete</button>
                    @endcan
                </td>
            </tr>
        @empty
            <td class="text-center" colspan="2">no data</td>
        @endforelse
    </table>

    {{ $plans->links('pagination-links') }}

    @livewire('plans.plan-create')
    @livewire('plans.plan-edit')
</div>
