<div wire:ignore.self class="modal fade" id="VisitFeedBackModal" tabindex="-1" aria-labelledby="VisitFeedBackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form wire:submit.prevent="saveVisitFeedback">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="VisitFeedBackModalLabel">Visit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="visit_comment" class="form-label">Visit Comment</label>
                    <input type="text" class="form-control" id="visit_comment" wire:model="visit_comment">
                    @error('visit_comment') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="client_goal" class="form-label">Lead Goal</label>
                    <input type="text" class="form-control" id="client_goal" wire:model="client_goal">
                    @error('client_goal') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-body">
                    <label for="selected_lead_status" class="form-label">Lead Status</label>
                    <select wire:model.change="selected_lead_status" class="form-select" id="selected_lead_status">
                        <option value="">Select Status</option>
                        @foreach ($lead_status as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    @error('selected_lead_status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @if (in_array($selected_lead_status,[App\Constants\ClientStatus::INTERESTED,App\Constants\ClientStatus::QUALIFED]))
                    <div class="modal-body">
                        <label for="next_call_date" class="form-label">Call Date</label>
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