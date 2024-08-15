<div wire:ignore.self class="modal fade livewiremodal" x-on:close-modal.window="on = false" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save" autocomplete="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPlanModalLabel">Create New Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cost" class="form-label">Cost</label>
                        <input type="text" class="form-control" id="cost" wire:model="cost">
                        @error('cost') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select class="form-control" id="branch_id" wire:model="branch_id">
                            <option value="">Select branch</option>
                            @foreach($allBranches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

