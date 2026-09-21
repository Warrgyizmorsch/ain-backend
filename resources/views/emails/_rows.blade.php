@forelse(($emails ?? []) as $email)
    @php
        $isSuperAdmin = Auth::check() && (int) Auth::user()->role_id === 1;
        $isUnread = (bool) (!$email->is_read && $email->folder !== 'sent' && $email->folder !== 'drafts' && !$email->is_draft);
        $isPending = $email->status === 'pending';
        $clientEmail = $email->customer_email;
        $rawFromEmail = $email->from_email;
        $displayFromEmail = $isSuperAdmin ? $rawFromEmail : mask_email_for_display($rawFromEmail);
        $displayCustomerEmail = $isSuperAdmin ? $clientEmail : mask_email_for_display($clientEmail);

        $clientContact = !empty($clientEmail)
            ? (($emailClientContacts ?? collect())[strtolower($clientEmail)] ?? null)
            : null;
        if (!$clientContact && !empty($rawFromEmail)) {
            $clientContact = ($emailClientContacts ?? collect())[strtolower($rawFromEmail)] ?? null;
        }

        $clientWhatsAppPhone = null;
        $clientWhatsAppUrl = null;
        if ($clientContact && !empty($clientContact->mobile_no)) {
            $clientWhatsAppPhone = \App\Http\Controllers\EmailController::formatWhatsAppPhone($clientContact->countrycode ?? '', $clientContact->mobile_no);
            if (!empty($clientWhatsAppPhone)) {
                $clientWhatsAppUrl = route('whatsapp.chat', ['phone' => $clientWhatsAppPhone]);
            }
        }
        $effectiveWhatsAppUrl = $clientWhatsAppUrl;

        static $cachedAllRowLabels = null;
        if ($cachedAllRowLabels === null) {
            $cachedAllRowLabels = (isset($allLabels) && $allLabels instanceof \Illuminate\Support\Collection)
                ? $allLabels
                : \App\Models\WhatsappChatLabel::forEmail()->ordered()->get();
        }
        $allRowLabels = $cachedAllRowLabels;

        if (isset($threadLabelsMap) && $threadLabelsMap instanceof \Illuminate\Support\Collection) {
            $rowLabels = $threadLabelsMap->get($email->thread_id) ?? collect();
            if ($rowLabels->isEmpty() && !empty($clientEmail)) {
                $rowLabels = $threadLabelsMap->get($clientEmail) ?? collect();
            }
        } else {
            $rowLabels = \App\Models\EmailThreadLabel::with('label')
                ->where('thread_id', $email->thread_id)
                ->when(empty($email->thread_id) && !empty($clientEmail), fn($q) => $q->orWhere('email', $clientEmail))
                ->get()
                ->unique('label_id');
        }
        $activeRowLabelIds = $rowLabels->pluck('label_id')->toArray();

        $senderDisplayName = $email->from_name ?: ($displayFromEmail ?: 'Unknown');
        if (!$isSuperAdmin && filter_var($email->from_name, FILTER_VALIDATE_EMAIL)) {
            $senderDisplayName = mask_email_for_display($email->from_name);
        }
        if ($email->folder === 'sent' || $email->direction === 'outbound') {
            $rawToEmail = $email->to_email;
            $displayToEmail = $isSuperAdmin ? $rawToEmail : mask_email_for_display($rawToEmail);
            $toName = $email->to_name ?: $displayToEmail;
            if (!$isSuperAdmin && filter_var($email->to_name, FILTER_VALIDATE_EMAIL)) {
                $toName = mask_email_for_display($email->to_name);
            }
            $senderDisplayName = 'To: ' . ($toName ?: 'Recipient');
        }

        $dateObj = $email->received_at ?: $email->created_at;
        $formattedDate = '';
        if ($dateObj) {
            if ($dateObj->isToday()) {
                $formattedDate = $dateObj->format('h:i A');
            } elseif ($dateObj->isCurrentYear()) {
                $formattedDate = $dateObj->format('M d');
            } else {
                $formattedDate = $dateObj->format('d/m/Y');
            }
        }
        $cleanSnippetText = preg_replace('/(On\s+[\s\S]*?wrote:[\s\S]*|-----Original Message-----[\s\S]*)/iu', '', $email->body_plain ?? '');
        $cleanSnippetText = trim(preg_replace('/\s+/', ' ', $cleanSnippetText));
        if (empty($cleanSnippetText)) {
            $cleanSnippetText = trim(preg_replace('/\s+/', ' ', strip_tags($email->body_plain ?: $email->body_html)));
        }
        $plainSnippet = Str::limit($cleanSnippetText, 120);
    @endphp

    <div class="gmail-row duralux-email-item {{ $isUnread ? 'unread' : 'is-read' }} {{ $isPending ? 'pending-email-item' : '' }}" 
         id="email-row-{{ $email->id }}" 
         onclick="openEmailThread({{ $email->id }})"
         tabindex="0"
         role="row">
        
        {{-- Left Controls: Checkbox & Star --}}
        <div class="gmail-row-controls duralux-item-left" onclick="event.stopPropagation();">
            <label class="gmail-checkbox-wrap" title="Select">
                <input class="form-check-input email-item-checkbox" type="checkbox" value="{{ $email->id }}" onchange="handleRowCheckboxChange(this, event)">
            </label>

            <button type="button" 
                    class="gmail-star-btn duralux-star-btn {{ $email->is_starred ? 'active text-warning' : '' }}" 
                    onclick="toggleStar({{ $email->id }}, this)" 
                    title="{{ $email->is_starred ? 'Starred' : 'Not starred' }}">
                <i class="fa {{ $email->is_starred ? 'fa-star' : 'fa-star-o' }}"></i>
            </button>

            @if($isUnread)
                <span class="gmail-unread-dot duralux-unread-dot" title="Unread email"></span>
            @endif
        </div>

        {{-- Sender Column with Copy & WhatsApp action buttons --}}
        <div class="gmail-row-sender duralux-email-sender" title="{{ $displayFromEmail }}">
            <span class="gmail-sender-text text-truncate">{{ $senderDisplayName }}</span>
            <span class="gmail-sender-actions ms-1 d-inline-flex align-items-center gap-1 flex-shrink-0" onclick="event.stopPropagation();">
                <button type="button" 
                        class="btn btn-icon btn-sm p-0 flex-shrink-0" 
                        style="width: 18px; height: 18px; min-width: 18px; border: none; background: transparent; color: #5f6368;" 
                        title="Copy Email: {{ $displayFromEmail }}" 
                        onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayFromEmail }}', 'Email copied!');">
                    <i class="fa fa-clone" style="font-size: 11px;"></i>
                </button>
                @if(!empty($effectiveWhatsAppUrl))
                <a href="{{ $effectiveWhatsAppUrl }}" 
                   target="_blank" 
                   class="btn btn-icon btn-sm p-0 flex-shrink-0 text-success d-inline-flex align-items-center justify-content-center" 
                   style="width: 18px; height: 18px; min-width: 18px; border: none; background: transparent; color: #25D366 !important;" 
                   title="WhatsApp: {{ $clientContact?->name ?: ($clientWhatsAppPhone ?: 'Open Chat') }}"
                   onclick="event.stopPropagation();">
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                </a>
                @endif
            </span>
        </div>

        {{-- Main Message Snippet & Labels (One Continuous Line) --}}
        <div class="gmail-row-content duralux-item-content">
            <div class="gmail-content-line duralux-email-body-preview">
                {{-- Gmail Style Labels --}}
                @if($rowLabels->count() > 0)
                    <span class="gmail-row-labels" id="row-labels-badges-{{ $email->id }}" onclick="event.stopPropagation();">
                        @foreach($rowLabels->take(3) as $rl)
                            @if($rl->label)
                                <span class="gmail-label-chip" style="--label-color: {{ $rl->label->color }};">
                                    <span class="label-dot"></span>
                                    {{ $rl->label->name }}
                                </span>
                            @endif
                        @endforeach
                        @if($rowLabels->count() > 3)
                            <span class="gmail-label-chip more">+{{ $rowLabels->count() - 3 }}</span>
                        @endif
                    </span>
                @else
                    <span id="row-labels-badges-{{ $email->id }}"></span>
                @endif

                <span class="gmail-row-subject duralux-email-subject {{ $isUnread ? 'fw-bold' : '' }}">
                    {{ $email->subject ?: '(No Subject)' }}
                </span>
                
                <span class="gmail-row-snippet">
                    — {{ $plainSnippet }}
                </span>
            </div>
        </div>

        {{-- Right Section: Date & Attachment + Quick Hover Actions --}}
        <div class="gmail-row-right duralux-item-right" onclick="event.stopPropagation();">
            {{-- Default View: Attachment & Date --}}
            <div class="gmail-date-wrap">
                @if(!empty($effectiveWhatsAppUrl))
                <a href="{{ $effectiveWhatsAppUrl }}"
                   target="_blank"
                   class="d-inline-flex align-items-center justify-content-center me-1"
                   style="color: #25D366 !important;"
                   title="WhatsApp: {{ $clientContact?->name ?: ($clientWhatsAppPhone ?: 'Open Chat') }}"
                   aria-label="Open in WhatsApp"
                   onclick="event.stopPropagation();">
                    <svg width="17" height="17" viewBox="0 0 16 16" fill="currentColor"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                </a>
                @endif

                @if($email->has_attachments)
                    <i class="fa fa-paperclip gmail-clip-icon text-muted" title="Has Attachment"></i>
                @endif

                @if($isPending)
                    <span class="badge bg-light-warning text-warning fs-9 py-0.5 px-1.5 fw-bold">
                        <i class="fa fa-clock-o me-0.5"></i> Pending
                    </span>
                @else
                    <span class="gmail-row-date duralux-email-time {{ $isUnread ? 'fw-bold text-dark' : '' }}">{{ $formattedDate }}</span>
                @endif
            </div>

            {{-- Gmail Hover Quick Actions Bar --}}
            <div class="gmail-hover-actions">
                <button type="button" 
                        class="gmail-hover-btn" 
                        title="Copy Email: {{ $displayFromEmail }}" 
                        onclick="crmCopyToClipboard('{{ $displayFromEmail }}', 'Email copied!');">
                    <i class="fa fa-clone" style="font-size: 14px;"></i>
                </button>

                @if(!empty($effectiveWhatsAppUrl))
                <a href="{{ $effectiveWhatsAppUrl }}"
                   target="_blank"
                   class="gmail-hover-btn gmail-hover-wa-btn d-inline-flex align-items-center justify-content-center"
                   style="color: #25D366 !important;"
                   title="WhatsApp: {{ $clientContact?->name ?: ($clientWhatsAppPhone ?: 'Open Chat') }}"
                   aria-label="Open in WhatsApp"
                   onclick="event.stopPropagation();">
                    <svg width="17" height="17" viewBox="0 0 16 16" fill="#25D366"><path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/></svg>
                </a>
                @endif

                <button type="button" 
                        class="gmail-hover-btn" 
                        title="Archive" 
                        onclick="archiveEmail({{ $email->id }})">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12l.94 1H5.12z"/></svg>
                </button>

                <button type="button" 
                        class="gmail-hover-btn" 
                        title="Move to Trash" 
                        onclick="deleteEmail({{ $email->id }})">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M15 4V3H9v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5zm2 15H7V6h10v13zM9 8h2v9H9zm4 0h2v9h-2z"/></svg>
                </button>

                <button type="button" 
                        class="gmail-hover-btn" 
                        id="btn-read-toggle-{{ $email->id }}"
                        title="Mark as {{ $email->is_read ? 'Unread' : 'Read' }}" 
                        onclick="toggleReadStatus({{ $email->id }}, {{ $email->is_read ? 'false' : 'true' }})">
                    @if($email->is_read)
                        {{-- Mark as Unread (Closed Mail) --}}
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    @else
                        {{-- Mark as Read (Open Mail) --}}
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M21.99 8c0-.72-.37-1.35-.94-1.7L12 1 2.95 6.3C2.38 6.65 2 7.28 2 8v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2l-.01-10zM12 3.32L19.99 8v.01L12 13 4 8.01V8l8-4.68zM4 18v-8.2l7.46 4.66c.16.1.35.15.54.15s.38-.05.54-.15L20 9.8V18H4z"/></svg>
                    @endif
                </button>

                {{-- Quick Labels Dropdown --}}
                <div class="dropdown d-inline-block">
                    <button type="button" 
                            class="gmail-hover-btn" 
                            data-bs-toggle="dropdown" 
                            title="Assign Labels">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.42zM13 20.01L4 11V4h7v-.01l9 9-7 7.02z"/><circle cx="6.5" cy="6.5" r="1.5"/></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" style="min-width: 220px;" onclick="event.stopPropagation();">
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                            <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Assign Labels</h6>
                            <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            @foreach($allRowLabels as $al)
                                <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-1 rounded hover-bg-light cursor-pointer mb-0">
                                    <input class="form-check-input row-label-chk-{{ $email->id }}" 
                                           type="checkbox" 
                                           value="{{ $al->id }}" 
                                           data-name="{{ $al->name }}" 
                                           data-color="{{ $al->color }}" 
                                           {{ in_array($al->id, $activeRowLabelIds) ? 'checked' : '' }} 
                                           onchange="saveRowEmailLabels('{{ $email->thread_id }}', '{{ $clientEmail }}', {{ $email->id }})">
                                    <span class="badge px-2 py-0.5 fs-9 fw-bold" style="background-color: {{ $al->color }}; color: #ffffff;">{{ $al->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="button" 
                        class="gmail-hover-btn" 
                        title="Open Thread" 
                        onclick="openEmailThread({{ $email->id }})">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                </button>
            </div>
        </div>
    </div>
@empty
    @if(!isset($isAppend) || !$isAppend)
        <div class="text-center py-16 gmail-empty-state">
            <div class="d-inline-flex p-4 rounded-circle bg-light-primary mb-3">
                <i class="fa fa-inbox fs-1 text-primary"></i>
            </div>
            <h5 class="fw-bold text-gray-800 mb-1">Your mail folder is empty</h5>
            <p class="text-muted fs-7 mb-4">No messages found in this mailbox or matching your filter.</p>
            <button type="button" class="btn btn-sm btn-primary fw-semibold px-4" onclick="openComposeModal()">
                <i class="fa fa-pencil me-1.5"></i> Compose New Email
            </button>
        </div>
    @endif
@endforelse
