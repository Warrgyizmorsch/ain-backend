<!-- Restore Lead Modal -->
<div class="modal fade" id="kt_modal_restore_lead{{ $lead['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restore Lead (<strong class="text-success">Order Code: {{ $lead['order_id'] }}</strong>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore this lead?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                
                <!-- Form for restoring lead -->
                <form action="{{ route('ll.lead.restore', $lead['id']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Lead Modal -->
<div class="modal fade" id="kt_modal_delete_lead{{ $lead['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Lead (<strong class="text-success">Order Code: {{ $lead['order_id'] }}</strong>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this lead?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                
                <!-- Form for deleting lead -->
                <form action="{{ route('ll.lead.delete', $lead['id']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
