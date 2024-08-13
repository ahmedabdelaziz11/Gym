<div>
    <div class="row mb-4">
        <div class="col-lg-12">
            <h2>Branches Management
                @can('branch-list')
                    <div class="float-end">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createBranchModal"> Create New Branch</button>
                    </div>
                @endcan
            </h2>
        </div>
    </div>

    <input type="text" wire:model.live.debounce.1000ms="search" name="search"  class="form-control mb-4" placeholder="Search Branches...">

    <table class="table table-striped table-hover">
        <tr>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @forelse ($branches as $branch)
            <tr>
                <td>{{ $branch->name }}</td>
                <td>
                    @can('branch-edit')
                    <button class="btn btn-info" wire:click="$dispatch('editBranch', { id: {{ $branch->id }} })" data-bs-toggle="modal" data-bs-target="#editBranchModal">Edit</button>
                    @endcan
                    @can('branch-delete')
                        <button class="btn btn-danger" wire:click="deleteBranch({{ $branch->id }})">Delete</button>
                    @endcan
                </td>
            </tr>
        @empty
            <td class="text-center" colspan="2">no data</td>
        @endforelse
    </table>

    {{ $branches->links('pagination-links') }}

    @livewire('branches.branch-create')
    @livewire('branches.branch-edit')
</div>
