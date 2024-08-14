<div>
    <div class="row mb-4">
        <div class="col-lg-12">
            <h2>Services Management
                @can('service-list')
                    <div class="float-end">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createServiceModal"> Create New Service</button>
                    </div>
                @endcan
            </h2>
        </div>
    </div>

    <input type="text" wire:model.live.debounce.1000ms="search" name="search"  class="form-control mb-4" placeholder="Search Services...">

    <table class="table table-striped table-hover">
        <tr>
            <th>Name</th>
            <th>Cost</th>
            <th>Branch</th>
            <th width="280px">Action</th>
        </tr>
        @forelse ($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ number_format($service->cost,2) }}</td>
                <td>{{ $service->showable->branch->name }}</td>
                <td>
                    @can('service-edit')
                    <button class="btn btn-info" wire:click="$dispatch('editService', { id: {{ $service->id }} })" data-bs-toggle="modal" data-bs-target="#editServiceModal">Edit</button>
                    @endcan
                    @can('service-delete')
                        <button class="btn btn-danger" wire:click="deleteService({{ $service->id }})">Delete</button>
                    @endcan
                </td>
            </tr>
        @empty
            <td class="text-center" colspan="2">no data</td>
        @endforelse
    </table>

    {{ $services->links('pagination-links') }}

    @livewire('services.service-create')
    @livewire('services.service-edit')
</div>
