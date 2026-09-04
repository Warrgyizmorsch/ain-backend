@forelse(($emails ?? []) as $email)
    @php
        $isUnread = (bool) (!$email->is_read && $email->folder !== 'sent' && $email->folder !== 'drafts' && !$email->is_draft);
        $isPending = $email->status === 'pending';
        $avatarLetter = strtoupper(substr($email->from_name ?: ($email->from_email ?: 'U'), 0, 1));
    @endphp
    <div class="duralux-email-item {{ $isUnread ? 'unread' : '' }} {{ $isPending ? 'pending-email-item' : '' }}" id="email-row-{{ $email->id }}" onclick="openEmailThread({{ $email->id }})">
        <div class="duralux-item-left" onclick="event.stopPropagation();">
            <div class="form-check m-0">
                <input class="form-check-input email-item-checkbox" type="checkbox" value="{{ $email->id }}">
            </div>
            <button type="button" class="duralux-star-btn {{ $email->is_starred ? 'active' : '' }}" onclick="toggleStar({{ $email->id }}, this)">
                <i class="fa {{ $email->is_starred ? 'fa-star' : 'fa-star-o' }}"></i>
            </button>
            @if($isUnread)
                <span class="duralux-unread-dot" title="Unread email"></span>
            @endif
        </div>

        <div class="duralux-item-content">
            <div class="duralux-email-sender">{{ $email->from_name ?: $email->from_email }}</div>
            <div class="duralux-email-body-preview">
                <div class="d-flex align-items-center gap-1 text-truncate">
                    <span class="duralux-email-subject">{{ $email->subject ?: '(No Subject)' }}</span>
                    <span class="text-muted">- {{ Str::limit(strip_tags($email->body_plain ?: $email->body_html), 90) }}</span>
                </div>
                @php
                    $clientEmail = $email->customer_email;
                    $rowLabels = \App\Models\EmailThreadLabel::with('label')
                        ->where('thread_id', $email->thread_id)
                        ->when(empty($email->thread_id) && !empty($clientEmail), fn($q) => $q->orWhere('email', $clientEmail))
                        ->get()
                        ->unique('label_id');
                @endphp
                {{-- 3 to 4 Labels Outside on the Row below message preview --}}
                <div class="d-flex align-items-center gap-1 flex-wrap mt-1" id="row-labels-badges-{{ $email->id }}">
                    @foreach($rowLabels->take(4) as $rl)
                        @if($rl->label)
                            <span class="badge px-2 py-0.5 fs-9 fw-bold d-inline-flex align-items-center gap-1" style="background-color: {{ $rl->label->color }}; color: #ffffff; font-size: 10.5px; border-radius: 4px;">
                                <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#ffffff;"></span> {{ $rl->label->name }}
                            </span>
                        @endif
                    @endforeach
                    @if($rowLabels->count() > 4)
                        <span class="badge bg-light text-muted border fs-9 px-1" style="font-size: 10px;">+{{ $rowLabels->count() - 4 }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="duralux-item-right" onclick="event.stopPropagation();">
            @if($email->has_attachments)
                <i class="fa fa-paperclip text-muted" title="Has Attachments"></i>
            @endif
            @if($isPending)
                <div class="duralux-email-time text-warning fw-semibold">
                    <i class="fa fa-clock-o me-1"></i> Pending
                </div>
            @else
                <div class="duralux-email-time">{{ optional($email->received_at ?: $email->created_at)->format('h:i A, d M') }}</div>
            @endif

            {{-- Quick Label Dropdown Directly From Outside Row --}}
            <div class="dropdown me-1" onclick="event.stopPropagation();">
                <button type="button" class="duralux-btn-icon" data-bs-toggle="dropdown" title="Assign Labels">
                    <i class="fa fa-tags text-primary" style="font-size: 13px;"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg" style="min-width: 220px;" onclick="event.stopPropagation();">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fs-8 text-muted fw-bold text-uppercase m-0">Assign Labels</h6>
                        <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        @php
                            $allRowLabels = \App\Models\WhatsappChatLabel::orderBy('name')->get();
                            $activeRowLabelIds = $rowLabels->pluck('label_id')->toArray();
                        @endphp
                        @foreach($allRowLabels as $al)
                            <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-1.5 rounded hover-bg-light cursor-pointer mb-0">
                                <input class="form-check-input row-label-chk-{{ $email->id }}" type="checkbox" value="{{ $al->id }}" data-name="{{ $al->name }}" data-color="{{ $al->color }}" {{ in_array($al->id, $activeRowLabelIds) ? 'checked' : '' }} onchange="saveRowEmailLabels('{{ $email->thread_id }}', '{{ $clientEmail }}', {{ $email->id }})">
                                <span class="badge px-2 py-0.5 fs-9 fw-bold" style="background-color: {{ $al->color }}; color: #ffffff;">{{ $al->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <button type="button" class="duralux-btn-icon" data-bs-toggle="dropdown">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="openEmailThread({{ $email->id }})"><i class="fa fa-eye me-2"></i> View</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleReadStatus({{ $email->id }}, {{ $email->is_read ? 'false' : 'true' }})"><i class="fa {{ $email->is_read ? 'fa-envelope' : 'fa-envelope-open-o' }} me-2"></i> Mark as {{ $email->is_read ? 'Unread' : 'Read' }}</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="setInlineComposerMode('reply', '{{ $email->from_email }}', '{{ addslashes($email->subject) }}')"><i class="fa fa-reply me-2"></i> Reply</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteEmail({{ $email->id }})"><i class="fa fa-trash-o me-2"></i> Delete</a></li>
                </ul>
            </div>
        </div>
    </div>
@empty
    @if(!isset($isAppend) || !$isAppend)
        <div class="text-center py-16">
            <div class="d-inline-flex p-4 rounded-circle bg-light-primary mb-3">
                <i class="fa fa-envelope-open-o fs-1 text-primary"></i>
            </div>
            <h5 class="fw-bold text-gray-800">No emails found</h5>
            <p class="text-muted fs-7">No messages in this folder or search criteria.</p>
            <button type="button" class="btn btn-sm btn-primary" onclick="openComposeModal()">
                <i class="fa fa-pencil me-1"></i> Send a New Email
            </button>
        </div>
    @endif
@endforelse
