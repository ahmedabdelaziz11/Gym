<div>
    <div class="row mb-4">
        <div class="col-lg-12">
            <h2>Users Management
                @can('user-list')
                    <div class="float-end">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal"> Create New User</button>
                    </div>
                @endcan
            </h2>
        </div>
    </div>

    <input type="text" wire:model.live.debounce.1000ms="search" name="search"  class="form-control mb-4" placeholder="Search Users...">

    <table class="table table-striped table-hover">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th width="280px">Action</th>
        </tr>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach ($user->getRoleNames() as $role)
                        <label class="badge badge-secondary text-dark">{{ $role }}</label>
                    @endforeach
                </td>
                <td>
                    @can('user-edit')
                    <button class="btn btn-info" wire:click="$dispatch('editUser', { id: {{ $user->id }} })" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                    @endcan
                    @can('user-delete')
                        <button class="btn btn-danger" wire:click="deleteUser({{ $user->id }})">Delete</button>
                    @endcan
                </td>
            </tr>
        @empty
            <td colspan="4">no data</td>
        @endforelse
    </table>

    {{ $users->links('pagination-links') }}

    @livewire('user-create')
    @livewire('user-edit')
</div>
