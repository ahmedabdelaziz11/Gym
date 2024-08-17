<div wire:ignore.self class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="update">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlanModalLabel">Edit Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" wire:model="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="branch_id" class="form-label">Duration</label>
                    <select class="form-control" id="days" wire:model="days">
                        <option value="">Select Duration</option>
                        <option value="30">1 Month</option>
                        <option value="60">2 Month</option>
                        <option value="90">3 Month</option>
                        <option value="120">4 Month</option>
                        <option value="150">5 Month</option>
                        <option value="180">6 Month</option>
                        <option value="210">7 Month</option>
                        <option value="240">8 Month</option>
                        <option value="270">9 Month</option>
                        <option value="300">10 Month</option>
                        <option value="330">11 Month</option>
                        <option value="360">12 Month</option>
                    </select>
                    @error('days') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="cost" class="form-label">Cost</label>
                    <input type="text" class="form-control" id="cost" wire:model="cost">
                    @error('cost') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="member_count" class="form-label">Member Limit</label>
                    <input type="number" class="form-control" id="member_count" wire:model="member_count">
                    @error('member_count') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="expired_at" class="form-label">Expire Date</label>
                    <input type="date" class="form-control" id="expired_at" wire:model="expired_at">
                    @error('expired_at') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="service_id" class="form-label">Services</label>
                    <div class="input-group mb-3">
                        <select class="form-control" id="service_id" wire:model="service_id" wire:change="addService">
                            <option value="">Select Service</option>
                            @foreach($allServices as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('service_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="modal-body">
                    <label for="services" class="form-label">Selected Services</label>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Count</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedServices as $index => $selectedService)
                            <tr>
                                <td>{{ $selectedService['name'] }}</td>
                                <td><input type="number" wire:model="selectedServices.{{ $index }}.count" min="1" class="form-control"></td>
                                <td><button type="button" class="btn btn-danger" wire:click="removeService({{ $index }})">Delete</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-body">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-control" id="branch_id" wire:model="branch_id">
                        <option value="">Select branch</option>
                        @foreach($allBranches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>