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