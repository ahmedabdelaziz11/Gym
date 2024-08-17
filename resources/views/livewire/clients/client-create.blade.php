<div wire:ignore.self class="modal fade livewiremodal" x-on:close-modal.window="on = false" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save" autocomplete="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientModalLabel">Create New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" wire:model="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="modal-body">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" wire:model="phone">
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="modal-body">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" wire:model="email">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="modal-body">
                    <label for="national_id" class="form-label">National Id</label>
                    <input type="text" class="form-control" id="national_id" wire:model="national_id">
                    @error('national_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="modal-body">
                    <label for="data_type" class="form-label">Data Type</label>
                    <select class="form-control" id="data_type" wire:model="data_type">
                        <option value="">Select Data Type</option>
                        @foreach ($allDataTypes as $key => $value )
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    @error('data_type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="modal-body">
                    <label for="user_id" class="form-label">seller</label>
                    <select class="form-control" id="user_id" wire:model="user_id">
                        <option value="">Select Seller</option>
                        @foreach ($allSellers as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

