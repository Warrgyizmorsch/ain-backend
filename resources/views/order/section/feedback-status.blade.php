<!-- Modal for confirmation -->
<div class="modal fade" id="statusModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Feedback Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('status.update', ['id' => $order->id]) }}" method="POST">
                @csrf
                
                <div class="modal-body">
                    <p>Change Status</p>
                    @if(Auth::user()->role_id == 4)
                    <select name="status" class="form-control" required>
                        <option value="" >Select status...</option>
                        <option <?php if($order->status_issue == "Client Discussion Done") { echo 'selected'; } ?> value="Client Discussion Done">Client Discussion Done</option>
                        <option <?php if($order->status_issue == "Issue Raised Again") { echo 'selected'; } ?> value="Issue Raised Again">Issue Raised Again</option>
                        <option <?php if($order->status_issue == "Retention") { echo 'selected'; } ?> value="Retention">Retention</option>
                    
                    </select>

                    @else
                    <select name="status" class="form-control" required>
                        <option value="" >Select status...</option>
                        <option <?php if($order->status_issue == "Issue Raised") { echo 'selected'; } ?> value="Issue Raised">Issue Raised</option>
                        <option <?php if($order->status_issue == "Client Discussion Done") { echo 'selected'; } ?> value="Client Discussion Done">Client Discussion Done</option>
                        <option <?php if($order->status_issue == "Writer discussion Done") { echo 'selected'; } ?> value="Writer discussion Done">Writer discussion Done</option>
                        <option <?php if($order->status_issue == "Work in progress") { echo 'selected'; } ?> value="Work in progress">Work in progress</option>
                        <option <?php if($order->status_issue == "Case Resolved") { echo 'selected'; } ?> value="Case Resolved">Case Resolved</option>
                        <option <?php if($order->status_issue == "Issue Raised Again") { echo 'selected'; } ?> value="Issue Raised Again">Issue Raised Again</option>
                        <option <?php if($order->status_issue == "Retention") { echo 'selected'; } ?> value="Retention">Retention</option>
                    </select>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="handleConfirmation({{ $order->id }})">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

