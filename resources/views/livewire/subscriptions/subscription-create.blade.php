<div wire:ignore.self class="modal modal-lg fade livewiremodal" x-on:close-modal.window="on = false" id="createSubscriptionModal" tabindex="-1" aria-labelledby="createSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save" autocomplete="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubscriptionModalLabel">Create New Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr style="margin: 0px">
                <div class="modal-body">
                    <div class="row m-1">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-12">
                                    <label for="phone" class="form-label">Client Phone</label>
                                    <input type="text" class="form-control" id="phone" wire:model="phone" wire:keyup.debounce.500ms="getUser">
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-2">
                                    <p class="text-muted mb-2 fw-medium">Last    Plan  : {{$last_plan_name}}</p>
                                    <p class="text-muted fw-medium mb-0">Expired At : {{$last_plan_expired_at}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center rounded-circle">
                                <img src="{{asset('assets/images/profile/user-1.jpg')}}" width="100" class="rounded-circle" alt="...">
                            </div>  
                            <h2 class="text-center m-0">{{ $client_name }}</h2>
                            <h3 class="text-center m-0">#{{ $client_code }}</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row m-1">
                        <div class="col-6">
                            <label for="plan_id" class="form-label">Plan</label>
                            <select class="form-control" id="plan_id" wire:model.change="plan_id">
                                <option value="">Select Plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
                                @endforeach
                            </select>
                            @error('plan_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" wire:model.change="start_date">
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if ($plan_id)
                            <div class="col-12 mt-3" style="overflow-x: scroll">
                                <table class="table table-bordered">
                                    <thead style="background-color: #615cfe;color:black">
                                        <tr>
                                            <th scope="col">Plan Name</th>
                                            <th scope="col">Days</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">End Date</th>
                                            <th scope="col">Services</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $plan_name }}</td>
                                            <td>{{ $plan_days }}</td>
                                            <td>{{ $amount_paid }}</td>
                                            <td>{{ $end_date }}</td>
                                            <td>
                                                @forelse ($plan->services as $service)
                                                    <div>{{ $service->name }} ({{ $service->pivot->count }})</div>
                                                @empty
                                                    <div>No services</div>
                                                @endforelse
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>    
                            </div>                        
                        @endif
                    </div>
                    <div class="row m-1">
                        <div class="col-12 mt-3">
                            <label for="amount_paid" class="form-label">Cost</label>
                            <input type="text" class="form-control" id="amount_paid" wire:model="amount_paid" readonly>
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

