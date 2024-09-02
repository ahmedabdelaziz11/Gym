<div wire:ignore.self class="modal fade livewiremodal" x-on:close-modal.window="on = false" id="createSubscriptionModal" tabindex="-1" aria-labelledby="createSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save" autocomplete="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubscriptionModalLabel">Create New Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-1">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" wire:model="phone">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row m-1">
                        <label for="client_name" class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="client_name" wire:model="client_name" readonly>
                    </div>
                    <div class="row m-1">
                        <label for="plan_id" class="form-label">Plan</label>
                        <select class="form-control" id="plan_id" wire:model="plan_id">
                            <option value="">Select Plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{$plan->id}}">{{$plan->name}}</option>
                            @endforeach
                        </select>
                        @error('plan_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row m-1">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" wire:model.change="start_date">
                        @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row m-1">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" wire:model="end_date" readonly>
                    </div>
                    <div class="row m-1">
                        <label for="amount_paid" class="form-label">Cost</label>
                        <input type="text" class="form-control" id="amount_paid" wire:model="amount_paid" readonly>
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

