<div wire:ignore.self class="modal modal-lg fade livewiremodal" x-on:close-modal.window="on = false" id="createLeadModal" tabindex="-1" aria-labelledby="createLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save" autocomplete="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLeadModalLabel">Create New Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr style="margin: 0px">
                <div class="modal-body m-2">
                    <div class="row">
                        <div class="col-6">
                            <label for="name" class="form-label">Name <span class="text-danger ">*</span></label>
                            <input type="text" class="form-control" id="name" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label for="phone" class="form-label">Phone <span class="text-danger ">*</span></label>
                            <input type="text" class="form-control" id="phone" wire:model="phone">
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="data_type" class="form-label">Data Type <span class="text-danger ">*</span></label>
                            <select class="form-control" id="data_type" wire:model="data_type">
                                <option value="">Select Data Type</option>
                                @foreach ($allDataTypes as $type )
                                    <option value="{{$type->value}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                            @error('data_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label for="user_id" class="form-label">seller <span class="text-danger ">*</span></label>
                            <select class="form-control" id="user_id" wire:model="user_id">
                                <option value="">Select Seller</option>
                                @foreach ($allSellers as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" wire:model="email">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label for="national_id" class="form-label">National Id</label>
                            <input type="text" class="form-control" id="national_id" wire:model="national_id">
                            @error('national_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
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

