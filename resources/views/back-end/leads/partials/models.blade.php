<!-- callback-model -->
<div class="modal fade custom-slide-right" id="chatModal" tabindex="-1" role="dialog" data-lead-id{{ $lead->id }} aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modals">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white   " id="">callback
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Chat Box -->
            <div id="chatContent" class="flex-grow-1 overflow-auto px-3 py-2">
                Loading chat...
            </div>

            <!-- Chat Input -->
            <div class="border-top p-3 d-flex">
                <input type="text" id="chatInput" class="form-control me-2" placeholder="Type your message...">
                <button class="btn btn-primary" onclick="sendChatMessage()">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- whatsapp template model #11 -->

<div class="modal fade" id="templateModal" tabindex="-1" role="dialog" aria-labelledby="templateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select WhatsApp Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            
            @if(!empty($lead->user))
                <form action="{{ url('/lead/whatsapp/' . $lead->user->id) }}" method="POST" style="display:inline;">
            @else
                <form action="#" method="POST" style="display:inline;" onsubmit="Swal.fire('Error', 'User not found for this lead!', 'error'); return false;">
            @endif
                @csrf
                <div class="modal-body">
                    <select id="templateDropdown" name="tempplate" class="form-control">
                        <option value="">Select Template</option>
                    </select>
                    <input type="hidden" id="templateName" name="template_name">
                </div>
                <div class="modal-footer">
                    @if(!empty($lead->user))
                        <button type="submit" class="btn btn-sm btn-success">
                            Send Template
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-secondary" disabled title="No user attached to this lead">
                            User Not Found
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Duplicate Lead Modal -->
<div class="modal fade" id="hideLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Duplicate Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-bold">Order ID</label>
                <input type="hidden" id="duplicate_lead_id">
                <input type="text" id="hide_order_id" class="form-control" placeholder="Enter Order ID">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="hideLeadByOrderId()">
                    <i class="fa fa-eye-slash"></i> Mark Duplicate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Client Review / Behaviour Modal -->
<div class="modal fade" id="clientReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body scroll-y pt-0 pb-10 mx-5">
                <div class="text-center mb-10">
                    <h3 class="mb-3">Client Behaviour</h3>
                    <div class="text-muted fw-bold fs-6">Add notes about client behaviour and interaction</div>
                </div>

                <form id="clientReviewForm" class="form">
                    <input type="hidden" id="review_user_id">

                    <div class="d-flex flex-column mb-8 fv-row">
                        <textarea class="form-control form-control-solid" rows="4" id="review_text" placeholder="Enter client behaviour details..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3 btn-sm">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveClientReview()">
                            Save Behaviour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>