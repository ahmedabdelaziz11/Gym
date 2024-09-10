<div wire:ignore.self class="modal fade" id="CallFeedBackModal" tabindex="-1" aria-labelledby="CallFeedBackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CallFeedBackModalLabel">Call Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="status" class="form-label">Call Status</label>
                    <select wire:model.change="status" class="form-select" id="status">
                        <option value="">Select Status</option>
                        <option value="ANSWER">ANSWER</option>
                        <option value="NOT_ANSWER">NOT_ANSWER</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @if ($status == 'ANSWER')
                    <div class="modal-body">
                        <label for="comment" class="form-label">Call Comment</label>
                        <input type="text" class="form-control" id="comment" wire:model="comment">
                        @error('comment') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($client_type == App\Enums\ClientType::LEAD->value)
                        <div class="modal-body">
                            <label for="selected_lead_status" class="form-label">Lead Status</label>
                            <select wire:model.change="selected_lead_status" class="form-select" id="selected_lead_status">
                                <option value="">Select Status</option>
                                @foreach ($lead_status as $status)
                                    <option value="{{$status->value}}">{{$status->name}}</option>
                                @endforeach
                            </select>
                            @error('selected_lead_status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if (in_array($selected_lead_status,[App\Enums\ClientStatus::INTERESTED->value,App\Enums\ClientStatus::QUALIFIED->value,App\Enums\ClientStatus::PENDING->value]))
                        <div class="modal-body">
                            <label for="next_call_date" class="form-label">Call Date</label>
                            <input type="date" class="form-control" id="next_call_date" wire:model="next_call_date">
                            @error('next_call_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    @endif
                @endif

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>