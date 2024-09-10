<div wire:ignore.self class="modal fade" id="VisitFeedBackModal" tabindex="-1" aria-labelledby="VisitFeedBackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="save">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="VisitFeedBackModalLabel">Visit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr style="margin: 0px">
                <div class="modal-body">
                    <label for="visit_comment" class="form-label">Visit Comment <span class="text-danger ">*</span></label>
                    <textarea name="visit_comment" id="visit_comment" rows="3" wire:model="visit_comment" class="form-control"></textarea>
                    @error('visit_comment') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="client_goal" class="form-label">Lead Goal <span class="text-danger ">*</span></label>
                    <input type="text" class="form-control" id="client_goal" wire:model="client_goal">
                    @error('client_goal') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="selected_lead_status" class="form-label">Lead Status <span class="text-danger ">*</span></label>
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
                        <label for="next_call_date" class="form-label">Call Date <span class="text-danger ">*</span></label>
                        <input type="date" class="form-control" id="next_call_date" wire:model="next_call_date">
                        @error('next_call_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>