@extends('layouts.app')

@section('content')
@php
    $contacts = $contacts ?? [];
    $messages = $messages ?? collect();
    $selectedContact = $selectedContact ?? null;
    $selectedName = $selectedContact['name'] ?? 'Select chat';
    $selectedPhone = $selectedPhone ?? ($selectedContact['phone'] ?? '');
    $selectedColor = $selectedContact['color'] ?? '#25d366';
    $panelDefinitions = $panelDefinitions ?? [];
    $enabledPanelKeys = $enabledPanelKeys ?? [];
    $selectedPanel = $selectedPanel ?? null;
    $panelRows = $panelRows ?? collect();
    $labels = $labels ?? collect();
    $selectedContactLabels = $selectedContactLabels ?? [];
    $activeLabels = $labels->whereIn('id', $selectedContactLabels);
    $allContactLabelMap = $allContactLabelMap ?? collect();
    $labelsById = $labels->keyBy('id');
    $mediaDisplayUrl = function (?string $url) {
        if (! $url) {
            return null;
        }

        return \Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])
            ? $url
            : url(ltrim($url, '/'));
    };
    $mediaDisplayType = function (?string $type, ?string $name = null, ?string $url = null) {
        $extension = strtolower(pathinfo((string) ($name ?: $url), PATHINFO_EXTENSION));

        if ($extension === 'webm' && \Illuminate\Support\Str::startsWith(strtolower((string) $name), 'voice-note-')) {
            return 'audio';
        }

        return $type;
    };
@endphp

{{-- ======================================================
     WhatsApp Business Chat — Premium UI
     ====================================================== --}}

<div class="wab-page" id="wabPage">
    @if(session('error'))
        <div class="alert alert-danger fw-bold m-3">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success fw-bold m-3">{{ session('success') }}</div>
    @endif

    {{-- ── CONTACT SIDEBAR ── --}}
    <aside class="wab-sidebar" id="wabSidebar">

        {{-- Header --}}
        <div class="wab-sidebar-header">
            <div class="wab-sidebar-header-left">
                <button type="button" class="wab-avatar wab-avatar--header wab-settings-trigger" data-bs-toggle="modal" data-bs-target="#waPanelSettingsModal" title="Chat Settings">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.22.36.58.6 1 .6h.1a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1 .6z"/></svg>
                </button>
                <div class="wab-sidebar-title">
                    <span class="wab-label-main">Chats</span>
                    <span class="wab-label-sub"><span class="wab-online-dot"></span> WhatsApp Business</span>
                </div>
            </div>
            <div class="wab-header-actions">
                <button type="button" class="wab-icon-btn" data-bs-toggle="modal" data-bs-target="#newWaChatModal" title="New Chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="11"/><line x1="8" y1="11" x2="8" y2="11"/><line x1="16" y1="11" x2="16" y2="11"/></svg>
                </button>
                <button type="button" class="wab-icon-btn" id="wabToggleSidebar" onclick="wabToggleSidebar(event)" title="Toggle Sidebar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>

        {{-- Search --}}
        <div class="wab-search-wrap">
            <div class="wab-search-box">
                <svg class="wab-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" placeholder="Search conversations…" id="wabSearchInput">
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="wab-tabs">
            <button class="wab-tab active" data-tab="all">All</button>
            <button class="wab-tab" data-tab="unread">Unread</button>
            <button class="wab-tab" data-tab="groups">Groups</button>
        </div>



        {{-- Contact List --}}
        @php
            $contacts = [
                ['id'=>1,'name'=>'Aarav Sharma',   'msg'=>'Please share the payment link.','time'=>'10:42','active'=>true, 'badge'=>2,'color'=>'#25d366','status'=>'online'],
                ['id'=>2,'name'=>'Maya Patel',     'msg'=>'Deadline confirmation needed.', 'time'=>'09:18','active'=>false,'badge'=>0,'color'=>'#00bcd4','status'=>'away'],
                ['id'=>3,'name'=>'Karan Singh',    'msg'=>'I uploaded the new files ✅',   'time'=>'Yest.','active'=>false,'badge'=>1,'color'=>'#ff7043','status'=>'offline'],
                ['id'=>4,'name'=>'Nisha Verma',    'msg'=>'Thank you for the update 🙏',   'time'=>'Mon',  'active'=>false,'badge'=>0,'color'=>'#ab47bc','status'=>'online'],
                ['id'=>5,'name'=>'Raj Mehta',      'msg'=>'Can we reschedule?',            'time'=>'Sun',  'active'=>false,'badge'=>3,'color'=>'#ffa726','status'=>'away'],
                ['id'=>6,'name'=>'Priya Iyer',     'msg'=>'Order placed successfully!',    'time'=>'Fri',  'active'=>false,'badge'=>0,'color'=>'#ef5350','status'=>'offline'],
            ];
        @endphp
        @php $contacts = $dynamicContacts ?? $contacts; @endphp

        <div class="wab-contact-list" id="wabContactList">
            @foreach($contacts as $c)
            @php
                $cPhone = $c['phone'] ?? '';
                $cLabelIds = $allContactLabelMap->get($cPhone, []);
                $cLabels = $cLabelIds ? $labels->whereIn('id', $cLabelIds) : collect();
            @endphp
            <div class="wab-contact-item {{ $c['active'] ? 'is-active' : '' }}" id="wab-contact-card-{{ preg_replace('/\D+/', '', $cPhone) }}" data-name="{{ strtolower($c['name']) }}" data-contact-id="{{ $c['id'] }}" data-url="{{ isset($c['phone']) ? route('whatsapp.chat', ['phone' => $c['phone']]) : '' }}" data-phone="{{ $cPhone }}" data-color="{{ $c['color'] }}" data-badge="{{ $c['badge'] ?? 0 }}" data-is-group="{{ !empty($c['is_group']) ? '1' : '0' }}">
                <div class="wab-avatar" style="background:{{ $c['color'] }}1a;color:{{ $c['color'] }}">
                    {{ strtoupper(substr($c['name'],0,1)) }}
                    <span class="wab-status-badge wab-status--{{ $c['status'] }}"></span>
                </div>
                <div class="wab-contact-info">
                    <div class="wab-contact-row-top">
                        <span class="wab-contact-name">{{ $c['name'] }}</span>
                        <span class="wab-contact-time">{{ $c['time'] }}</span>
                    </div>
                    <div class="wab-contact-row-bottom">
                        <span class="wab-contact-preview">{{ $c['msg'] }}</span>
                        <div class="wab-contact-row-right d-flex align-items-center gap-1">
                            @if($c['badge'])
                                <span class="wab-badge">{{ $c['badge'] }}</span>
                            @endif
                            <button type="button" class="wab-quick-tag-btn" onclick="event.stopPropagation(); openQuickLabelModal('{{ $cPhone }}', '{{ addslashes($c['name']) }}', {{ json_encode($cLabelIds) }})" title="Assign Labels">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            </button>
                        </div>
                    </div>
                    {{-- 3 to 4 Labels Outside on the Contact Item --}}
                    @if($cLabels->isNotEmpty())
                        <div class="wab-contact-label-tags d-flex flex-wrap gap-1 mt-1 pt-1" id="wab-contact-tags-{{ preg_replace('/\D+/', '', $cPhone) }}">
                            @foreach($cLabels->take(4) as $lbl)
                                <span class="wab-contact-tag-chip" style="background:{{ $lbl->color }}1f;color:{{ $lbl->color }};border:1px solid {{ $lbl->color }}4d; font-size: 11px; padding: 1.5px 6px; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                                    <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:{{ $lbl->color }};"></span>{{ $lbl->name }}
                                </span>
                            @endforeach
                            @if($cLabels->count() > 4)
                                <span class="badge bg-light text-muted border fs-9 px-1.5 py-0.5" style="font-size: 10px;">+{{ $cLabels->count() - 4 }}</span>
                            @endif
                        </div>
                    @else
                        <div class="wab-contact-label-tags d-flex flex-wrap gap-1 mt-1 pt-1" id="wab-contact-tags-{{ preg_replace('/\D+/', '', $cPhone) }}"></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        {{-- Contact List Bottom Infinite Scroll Loader --}}
        <div id="wabContactListLoader" class="text-center py-2 d-none" style="width:100%">
            <div class="spinner-border spinner-border-sm text-success" role="status" style="width:1rem;height:1rem">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="ms-1 fs-9 text-muted fw-bold">Loading more...</span>
        </div>
    </aside>

    {{-- ── CONVERSATION PANEL ── --}}
    <section class="wab-conversation" style="position:relative">

        {{-- Instant Chat Preloader / Skeleton --}}
        <div id="wabChatPreloader" class="wab-chat-preloader d-none">
            <div class="spinner-border text-success" role="status" style="width:2.2rem;height:2.2rem">
                <span class="visually-hidden">Loading messages...</span>
            </div>
            <div class="mt-2 text-dark fw-bold fs-7" id="wabChatPreloaderText">Loading conversation...</div>
        </div>

        @unless($selectedPhone)
            <div class="wab-blank-chat">
                <div class="wab-blank-actions">
                    <button type="button">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span>Send document</span>
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newWaChatModal">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        <span>Add contact</span>
                    </button>
                    <button type="button">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                        <span>Ask AI</span>
                    </button>
                </div>
            </div>
        @endunless

        {{-- Conv Header --}}
        <div class="wab-conv-header {{ !$selectedPhone ? 'd-none' : '' }}">
            <div class="wab-conv-header-left">
                <button type="button" class="wab-icon-btn wab-mobile-back" id="wabMobileBack">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <div class="wab-avatar wab-avatar--conv wab-profile-trigger" id="wabOpenProfilePanel" style="background:{{ $selectedColor }}1a;color:{{ $selectedColor }}" title="Contact info" role="button">{{ strtoupper(substr($selectedName,0,1)) }}</div>
                <div class="wab-conv-user-info" id="wabOpenProfilePanelText" style="cursor:pointer" role="button">
                    <div class="wab-conv-name">{{ $selectedName }}</div>
                    <div class="wab-conv-status">
                        <span class="wab-online-dot"></span>
                        <span id="wabTypingLabel">{{ $selectedPhone ?: 'ready' }}</span>
                    </div>
                    @if($activeLabels->isNotEmpty())
                        <div class="wab-chat-label-row">
                            @foreach($activeLabels as $label)
                                <span style="background:{{ $label->color }}1a;color:{{ $label->color }};border:1px solid {{ $label->color }}30">{{ $label->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="wab-conv-actions" id="wabConvActions">
                <div id="wabChatActionButtons" class="d-flex align-items-center gap-2 {{ !$selectedPhone ? 'd-none' : '' }}">
                    {{-- Check Leads Button --}}
                    <button type="button" class="wab-label-btn" style="background:#e3f2fd;color:#1565c0;border:1px solid #bbdefb" data-bs-toggle="modal" data-bs-target="#waCheckLeadsModal" id="waHeaderCheckLeadsBtn" title="Check all Leads for this customer">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Check Leads
                    </button>

                    {{-- Check Orders Button --}}
                    <button type="button" class="wab-label-btn" style="background:#f3e5f5;color:#6a1b9a;border:1px solid #e1bee7" data-bs-toggle="modal" data-bs-target="#waCheckOrdersModal" id="waHeaderCheckOrdersBtn" title="Check all Orders for this customer">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        Check Orders
                    </button>

                    {{-- Dynamic Lead Action Button (Lead #ID or Create Lead) --}}
                    <div id="waHeaderLeadBtnWrap" class="d-inline-flex">
                        @if(isset($existingLead) && $existingLead)
                            <a href="{{ route('leadedit', $existingLead->id) }}" target="_blank" class="wab-label-btn" style="background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9" title="View CRM Lead #{{ $existingLead->order_id ?? $existingLead->id }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7.5" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                Lead #{{ $existingLead->order_id ?? $existingLead->id }}
                            </a>
                        @else
                            <button type="button" class="wab-label-btn" style="background:#00a884;color:#fff;border:none" data-bs-toggle="modal" data-bs-target="#waCreateLeadModal" title="Create Lead with this WhatsApp Customer">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                Create Lead
                            </button>
                        @endif
                    </div>

                    {{-- Label Chat Button --}}
                    <button type="button" class="wab-label-btn" data-bs-toggle="modal" data-bs-target="#waAssignLabelsModal" id="waHeaderLabelChatBtn" title="Label chat">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Label chat
                    </button>
                </div>

                <button class="wab-icon-btn" id="wabOpenProfileBtn2" title="Contact Info">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </button>

                <div class="wab-chat-more {{ !$selectedPhone ? 'd-none' : '' }}" id="wabChatMoreWrapper">
                    <button class="wab-icon-btn" id="wabChatMoreBtn" title="More options">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                    </button>
                    <div class="wab-chat-menu" id="wabChatMenu">
                        <button type="button" id="wabMarkUnreadBtn">Mark as unread</button>
                        <a href="{{ route('whatsapp.chat') }}" id="wabCloseChatBtn">Close chat</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages Body --}}
        <div class="wab-messages-body {{ !$selectedPhone ? 'd-none' : '' }}" id="wabMessagesBody" data-selected-phone="{{ $selectedPhone }}" data-last-message-id="{{ optional($messages->last())->id ?? 0 }}" data-first-message-id="{{ optional($messages->first())->id ?? 0 }}" data-has-more-older="{{ ($hasMoreOlderMessages ?? false) ? '1' : '0' }}">

            {{-- Older Messages Spinner (Scroll Up) --}}
            <div id="wabOlderMessagesLoader" class="text-center py-2 {{ ($hasMoreOlderMessages ?? false) ? '' : 'd-none' }}" style="width:100%">
                <div class="spinner-border spinner-border-sm text-secondary" role="status" style="width:1.1rem;height:1.1rem">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2 fs-9 text-muted fw-bold">Loading older messages...</span>
            </div>

            @if($selectedPanel && isset($panelDefinitions[$selectedPanel]))
                <div class="wab-panel-card">
                    <div class="wab-panel-card-head">
                        <div style="display:flex;align-items:center;gap:8px">
                            <span class="wab-panel-card-badge">{{ strtoupper($panelDefinitions[$selectedPanel]['short']) }}</span>
                            <div>
                                <strong>{{ $panelDefinitions[$selectedPanel]['label'] }} Orders</strong>
                                <span>{{ $panelRows->count() }} records</span>
                            </div>
                        </div>
                        <a href="{{ route('whatsapp.chat', array_filter(['phone' => $selectedPhone])) }}" class="wab-panel-close-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Close
                        </a>
                    </div>
                    <div class="wab-panel-table-wrap">
                        <table class="wab-panel-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Title</th>
                                    <th>Order Date</th>
                                    <th>Delivery</th>
                                    <th>Status</th>
                                    <th>Ticket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($panelRows as $i => $row)
                                    @php
                                        $statusClass = match(strtolower($row->projectstatus ?? '')) {
                                            'completed','delivered' => 'wab-status-pill--success',
                                            'failed','cancelled' => 'wab-status-pill--danger',
                                            'working','in progress' => 'wab-status-pill--warning',
                                            default => 'wab-status-pill--neutral',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="wab-table-num">{{ $i+1 }}</td>
                                        <td><strong>{{ $row->order_id }}</strong></td>
                                        <td>{{ \Illuminate\Support\Str::limit($row->title ?? 'N/A', 32) }}</td>
                                        <td>{{ !empty($row->order_date) && strtotime($row->order_date) ? date('d M', strtotime($row->order_date)) : '—' }}</td>
                                        <td>{{ !empty($row->delivery_date) && strtotime($row->delivery_date) ? date('d M', strtotime($row->delivery_date)) : '—' }}</td>
                                        <td><span class="wab-status-pill {{ $statusClass }}">{{ $row->projectstatus ?? 'N/A' }}</span></td>
                                        <td>{{ $row->feedback_ticket ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" style="text-align:center;padding:20px;color:#8696a0">No records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @php $lastRenderedDate = null; @endphp
            @forelse($messages->filter(fn($message) => trim($message->message ?? '') !== '' || $message->media_url) as $message)
                @php
                    $messageDate = optional($message->created_at)->toDateString();
                    $messageDateLabel = optional($message->created_at)->isToday()
                        ? 'Today'
                        : (optional($message->created_at)->isYesterday() ? 'Yesterday' : optional($message->created_at)->format('d M Y'));
                    $messageMediaType = $mediaDisplayType($message->media_type, $message->media_name, $message->media_url);
                @endphp
                @if($messageDate && $messageDate !== $lastRenderedDate)
                    <div class="wab-date-badge" data-date-key="{{ $messageDate }}">{{ $messageDateLabel }}</div>
                    @php $lastRenderedDate = $messageDate; @endphp
                @endif
                <div class="wab-msg-row {{ $message->direction === 'inbound' ? 'wab-incoming' : 'wab-outgoing' }}" data-message-id="{{ $message->id }}">
                    <div class="wab-msg-bubble {{ $messageMediaType ? 'wab-bubble--media wab-bubble--' . $messageMediaType : '' }}">
                        @if($message->media_url)
                            @php
                                $messageMediaUrl = $mediaDisplayUrl($message->media_url);
                            @endphp
                            @if($messageMediaType === 'image')
                                <a href="{{ $messageMediaUrl }}" target="_blank" class="wab-media-img-link" download="{{ $message->media_name }}">
                                    <img src="{{ $messageMediaUrl }}" class="wab-media-img" alt="{{ $message->media_name }}" loading="lazy">
                                </a>
                            @elseif($messageMediaType === 'video')
                                <video class="wab-media-video" controls preload="metadata">
                                    <source src="{{ $messageMediaUrl }}">
                                </video>
                            @elseif($messageMediaType === 'audio')
                                <div class="wab-voice-card">
                                    <button type="button" class="wab-voice-play" title="Play audio">
                                        <svg class="wab-voice-play-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                        <svg class="wab-voice-pause-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" hidden><path d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg>
                                    </button>
                                    <div class="wab-voice-wave">
                                        @for($i = 0; $i < 24; $i++)
                                            <span style="height: {{ 7 + (($i * 5) % 17) }}px"></span>
                                        @endfor
                                    </div>
                                    <span class="wab-voice-time">0:00</span>
                                    <audio class="wab-voice-audio" preload="metadata" src="{{ $messageMediaUrl }}"></audio>
                                </div>
                            @else
                                @php
                                    $ext = strtolower(pathinfo($message->media_name ?? '', PATHINFO_EXTENSION));
                                    $docColor = match($ext) {
                                        'pdf' => '#ef4444', 'doc','docx' => '#2563eb',
                                        'xls','xlsx' => '#16a34a', 'ppt','pptx' => '#ea580c',
                                        'zip','rar' => '#7c3aed', default => '#64748b',
                                    };
                                @endphp
                                <a href="{{ $messageMediaUrl }}" target="_blank" class="wab-media-doc-card" download="{{ $message->media_name }}">
                                    <div class="wab-media-doc-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $docColor }}" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        <span class="wab-doc-ext" style="color:{{ $docColor }}">{{ strtoupper($ext) }}</span>
                                    </div>
                                    <div class="wab-media-doc-info">
                                        <span class="wab-media-doc-name">{{ \Illuminate\Support\Str::limit($message->media_name ?? 'File', 30) }}</span>
                                        <span class="wab-media-doc-size">{{ $message->media_size ? number_format($message->media_size / 1024, 0) . ' KB' : '' }}</span>
                                    </div>
                                    <svg class="wab-doc-dl" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8696a0" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            @endif
                            @if(trim($message->message ?? '') !== '')
                                <div class="wab-media-caption">{{ $message->message }}</div>
                            @endif
                        @else
                            {{ $message->message }}
                        @endif
                        <div class="wab-msg-meta">
                            <span class="wab-msg-time">{{ optional($message->created_at)->format('H:i') }}</span>
                            @if($message->direction === 'outbound')
                                @php
                                    $waStatus = strtolower($message->status ?? 'sent');
                                    $tickStatus = in_array($waStatus, ['read', 'delivered', 'failed', 'undelivered'], true) ? $waStatus : 'sent';
                                    $tickColor = $tickStatus === 'read' ? '#53bdeb' : (in_array($tickStatus, ['failed', 'undelivered'], true) ? '#d93025' : '#8696a0');
                                @endphp
                                <span class="wab-tick wab-tick--{{ $tickStatus }}" data-status="{{ $tickStatus }}" title="{{ ucfirst($tickStatus) }}">
                                    @if(in_array($tickStatus, ['failed', 'undelivered'], true))
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="5.8" stroke="{{ $tickColor }}" stroke-width="1.6"/><path d="M7 3.8v3.7" stroke="{{ $tickColor }}" stroke-width="1.8" stroke-linecap="round"/><circle cx="7" cy="10" r="1" fill="{{ $tickColor }}"/></svg>
                                    @elseif(in_array($tickStatus, ['delivered', 'read'], true))
                                        <svg width="18" height="12" viewBox="0 0 20 12" fill="none"><path d="M1 6.5l3.2 3.2L11.8 2" stroke="{{ $tickColor }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 9.7L17 1" stroke="{{ $tickColor }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @else
                                        <svg width="14" height="10" viewBox="0 0 18 12" fill="none"><path d="M1 6l4 4L17 1" stroke="{{ $tickColor }}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="wab-msg-row wab-incoming wab-empty-message">
                    <div class="wab-msg-bubble">
                        No messages yet.
                        <div class="wab-msg-meta"><span class="wab-msg-time">{{ now()->format('H:i') }}</span></div>
                    </div>
                </div>
            @endforelse

            @if(false)
            <div class="wab-msg-row wab-incoming">
                <div class="wab-msg-bubble">
                    Hi, can you confirm my order deadline?
                    <div class="wab-msg-meta"><span class="wab-msg-time">10:31</span></div>
                </div>
            </div>

            <div class="wab-msg-row wab-outgoing">
                <div class="wab-msg-bubble">
                    Sure! Your current delivery deadline is shown in the order panel. Please check it there.
                    <div class="wab-msg-meta">
                        <span class="wab-msg-time">10:34</span>
                        <span class="wab-tick wab-tick--read">
                            <svg width="14" height="10" viewBox="0 0 18 12" fill="none"><path d="M1 6l4 4L17 1" stroke="#53bdeb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 10l4-4" stroke="#53bdeb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            <div class="wab-msg-row wab-incoming">
                <div class="wab-msg-bubble">
                    Please share the payment link 🙏
                    <div class="wab-msg-meta"><span class="wab-msg-time">10:42</span></div>
                </div>
            </div>

            <div class="wab-msg-row wab-outgoing">
                <div class="wab-msg-bubble">
                    Of course! Here's your secure payment link: <a href="#" class="wab-link">pay.example.com/inv/2024</a>
                    <div class="wab-msg-meta">
                        <span class="wab-msg-time">10:44</span>
                        <span class="wab-tick wab-tick--sent">
                            <svg width="14" height="10" viewBox="0 0 18 12" fill="none"><path d="M1 6l4 4L17 1" stroke="#8696a0" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Typing indicator --}}
            <div class="wab-msg-row wab-incoming wab-typing-row" id="wabTypingRow" hidden>
                <div class="wab-msg-bubble wab-typing-bubble">
                    <span class="wab-typing-dot"></span>
                    <span class="wab-typing-dot"></span>
                    <span class="wab-typing-dot"></span>
                </div>
            </div>

        </div>

        {{-- Conv Footer --}}
        <form class="wab-conv-footer {{ !$selectedPhone ? 'd-none' : '' }}" method="POST" action="{{ route('whatsapp.chat.send') }}">
            @csrf
            <input type="hidden" name="phone" value="{{ $selectedPhone }}">
            <div class="wab-footer-actions-left">
                <button type="button" class="wab-icon-btn wab-emoji-btn" id="wabEmojiBtn" title="Emoji">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </button>
                <button type="button" class="wab-icon-btn wab-plus-btn" id="wabAttachBtn" title="Attach">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
                <div class="wab-attach-menu" id="wabAttachMenu">
                    <button type="button" data-attach-type="document">
                        <span class="wab-attach-icon wab-attach-icon--document">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6 2h8l5 5v15H6V2zm7 1.8V8h4.2L13 3.8z"/></svg>
                        </span>
                        <span>Document</span>
                    </button>
                    <button type="button" data-attach-type="media">
                        <span class="wab-attach-icon wab-attach-icon--media">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 5h16v14H4V5zm2 2v8.5l3.2-3.2 2.6 2.6 3.8-4.8L18 13.3V7H6zm2.2 3a1.6 1.6 0 1 0 0-3.2 1.6 1.6 0 0 0 0 3.2z"/></svg>
                        </span>
                        <span>Photos &amp; videos</span>
                    </button>
                </div>
                <input type="file" id="wabDocumentInput" class="wab-media-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.csv" multiple hidden>
                <input type="file" id="wabPhotoVideoInput" class="wab-media-input" accept="image/*,video/*" multiple hidden>
            </div>
            <div class="wab-input-wrap">
                <input type="text" class="wab-input" id="wabInput" name="message" placeholder="Type a message…" autocomplete="off" {{ $selectedPhone ? '' : 'disabled' }}>
            </div>
            <div class="wab-audio-review" id="wabAudioReview" hidden>
                <button type="button" class="wab-audio-icon-btn" id="wabAudioDelete" title="Delete recording">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 15H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                </button>
                <button type="button" class="wab-audio-icon-btn" id="wabAudioPlay" title="Play recording">
                    <svg class="wab-audio-play-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    <svg class="wab-audio-pause-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" hidden><path d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg>
                </button>
                <div class="wab-audio-wave" id="wabAudioWave" aria-hidden="true">
                    @for($i = 0; $i < 22; $i++)
                        <span style="height: {{ 8 + (($i * 7) % 18) }}px"></span>
                    @endfor
                </div>
                <span class="wab-audio-time" id="wabAudioTime">0:00</span>
            </div>
            <button type="button" class="wab-mic-btn" id="wabMicBtn" title="Record audio" {{ $selectedPhone ? '' : 'disabled' }}>
                <svg class="wab-mic-icon" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><path d="M12 19v3"/><path d="M8 22h8"/></svg>
                <svg class="wab-stop-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" hidden><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
            </button>
            <button type="submit" class="wab-send-btn" id="wabSendBtn" title="Send" {{ $selectedPhone ? '' : 'disabled' }}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </form>

        {{-- Emoji Quick Panel --}}
        <div class="wab-emoji-panel" id="wabEmojiPanel">
            @foreach(['😊','😂','❤️','👍','🙏','😍','🎉','✅','🔥','💯','😎','👋','🤝','💪','⭐','🕐','📎','📁','📞','📧','💬','🎯','📊','✍️','👀','💡','🚀','⚡'] as $emoji)
                <button class="wab-emoji-item" data-emoji="{{ $emoji }}">{{ $emoji }}</button>
            @endforeach
        </div>

        <div class="wab-upload-review" id="wabUploadReview" hidden>
            <div class="wab-upload-review-head">
                <button type="button" class="wab-upload-close" id="wabUploadClose" title="Close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <strong id="wabUploadFileName">File</strong>
            </div>
            <div class="wab-upload-review-body">
                <div class="wab-upload-preview" id="wabUploadPreview"></div>
            </div>
            <div class="wab-upload-tray" id="wabUploadTray"></div>
            <div class="wab-upload-review-footer">
                <input type="text" class="wab-upload-caption" id="wabUploadCaption" placeholder="Type a message" autocomplete="off">
                <button type="button" class="wab-upload-send" id="wabUploadSend" title="Send">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/></svg>
                </button>
            </div>
        </div>

    </section>
</div>

{{-- ══════════════════════════════════════════════════
     New Chat Modal
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="newWaChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content wab-modal-content">
            <form method="POST" action="{{ route('whatsapp.chat.start') }}">
                @csrf
            <div class="modal-header wab-modal-header">
                <div class="wab-modal-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h5 class="modal-title wab-modal-title">Start New Chat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body wab-modal-body">
                <label class="wab-form-label">Country Code</label>
                <div class="wab-modal-row">
                    <select class="wab-form-select" name="country_code" style="flex:0 0 110px">
                        <option value="+91">🇮🇳 +91</option>
                        <option value="+44">🇬🇧 +44</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+61">🇦🇺 +61</option>
                        <option value="+971">🇦🇪 +971</option>
                    </select>
                    <input type="tel" class="wab-form-input" name="mobile" placeholder="Mobile number" required>
                </div>
                <label class="wab-form-label mt-3">First Message</label>
                <textarea class="wab-form-input" name="message" rows="3" placeholder="Hi, I wanted to reach out about…" style="resize:none;padding-top:10px"></textarea>
                <p class="wab-form-note">This will open a new conversation thread via your WhatsApp Business API.</p>
            </div>
            <div class="modal-footer wab-modal-footer">
                <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="wab-btn wab-btn--primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Open Chat
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     IMPORT CONTACTS MODAL
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waImportContactsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content wab-modal-content">
            <div class="modal-header wab-modal-header">
                <div class="wab-modal-icon" style="background:linear-gradient(135deg,#25d366,#128c7e)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <h5 class="modal-title wab-modal-title">Import Contacts from File</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" id="waImportModalClose"></button>
            </div>
            <div class="modal-body wab-modal-body" style="padding:20px">

                {{-- Step 1: File Upload --}}
                <div id="waImportStep1">
                    {{-- Format Info --}}
                    <div class="wa-import-info-bar">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#128c7e" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>Supported formats: <strong>CSV</strong> &amp; <strong>Excel (.xlsx)</strong>. Required columns: <code>name</code>, <code>phone</code>. Optional: <code>country_code</code> (e.g. +91).</span>
                        <a href="#" id="waDownloadSample" class="wa-import-sample-link">Download sample CSV</a>
                    </div>

                    {{-- Drop Zone --}}
                    <div class="wa-import-drop" id="waImportDrop">
                        <div class="wa-import-drop-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="13" x2="12" y2="17"/><polyline points="9 16 12 19 15 16"/></svg>
                        </div>
                        <div class="wa-import-drop-title">Drop your file here</div>
                        <div class="wa-import-drop-sub">or <label for="waImportFileInput" class="wa-import-browse-link">browse to upload</label></div>
                        <div class="wa-import-drop-hint">CSV or XLSX — max 5 MB</div>
                        <input type="file" id="waImportFileInput" accept=".csv,.xlsx,.xls" style="display:none">
                    </div>

                    {{-- Selected File Info --}}
                    <div class="wa-import-file-info" id="waImportFileInfo" style="display:none">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span id="waImportFileName">filename.csv</span>
                        <span id="waImportFileSize" class="wa-import-file-size"></span>
                        <button type="button" class="wa-import-file-remove" id="waImportFileRemove" title="Remove file">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>

                    {{-- Error display --}}
                    <div class="wa-import-error" id="waImportError" style="display:none"></div>
                </div>

                {{-- Step 2: Preview --}}
                <div id="waImportStep2" style="display:none">
                    <div class="wa-import-preview-header">
                        <div class="wa-import-preview-title">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Preview — <span id="waImportRowCount">0</span> contacts ready to import</span>
                        </div>
                        <button type="button" class="wa-import-back-btn" id="waImportBackBtn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                            Change file
                        </button>
                    </div>
                    <div class="wa-import-table-wrap">
                        <table class="wa-import-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="waImportPreviewBody"></tbody>
                        </table>
                    </div>
                    <div class="wa-import-skipped" id="waImportSkipped" style="display:none">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ffa726" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r="1" fill="#ffa726"/></svg>
                        <span id="waImportSkippedText"></span>
                    </div>
                </div>

                {{-- Step 3: Progress --}}
                <div id="waImportStep3" style="display:none;text-align:center;padding:28px 0">
                    <div class="wa-import-spinner"></div>
                    <div class="wa-import-progress-text" id="waImportProgressText">Importing contacts…</div>
                    <div class="wa-import-progress-bar-wrap"><div class="wa-import-progress-bar" id="waImportProgressBar" style="width:0%"></div></div>
                </div>

                {{-- Step 4: Done --}}
                <div id="waImportStep4" style="display:none;text-align:center;padding:28px 0">
                    <div class="wa-import-done-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#25d366" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="8 12 11 15 16 9" stroke-width="2.2"/></svg>
                    </div>
                    <div class="wa-import-done-title" id="waImportDoneTitle">Import Complete!</div>
                    <div class="wa-import-done-sub" id="waImportDoneSub"></div>
                </div>

            </div>
            <div class="modal-footer wab-modal-footer" id="waImportFooter">
                <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="wab-btn wab-btn--primary" id="waImportPreviewBtn" disabled>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Preview
                </button>
                <button type="button" class="wab-btn wab-btn--primary" id="waImportSubmitBtn" style="display:none">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import All
                </button>
                <button type="button" class="wab-btn wab-btn--primary" id="waImportDoneBtn" style="display:none" data-bs-dismiss="modal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Done
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     CHAT SETTINGS MODAL (Panel toggles + Custom Labels)
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waPanelSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content wab-modal-content">
            {{-- Panel Settings Form --}}
            <form method="POST" action="{{ route('whatsapp.chat.panel-settings') }}">
                @csrf
                <div class="modal-header wab-modal-header">
                    <div class="wab-modal-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.22.36.58.6 1 .6h.1a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1 .6z"/></svg>
                    </div>
                    <h5 class="modal-title wab-modal-title">Chat Settings</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body wab-modal-body" style="padding-bottom:8px">
                    <div class="wab-section-label">Show Info Panels</div>
                    <div class="wab-toggle-list">
                        @foreach($panelDefinitions as $key => $panel)
                            <label class="wab-toggle-row">
                                <div class="wab-toggle-row-left">
                                    <span class="wab-panel-short-badge">{{ $panel['short'] }}</span>
                                    <span class="wab-toggle-row-name">{{ $panel['label'] }}</span>
                                </div>
                                <div class="wab-ios-toggle">
                                    <input type="checkbox" name="panels[{{ $key }}]" value="1" {{ in_array($key, $enabledPanelKeys, true) ? 'checked' : '' }} id="panel_{{ $key }}">
                                    <span class="wab-ios-slider"></span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer wab-modal-footer">
                    <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="wab-btn wab-btn--primary">Save Panels</button>
                </div>
            </form>

            {{-- Custom Label Creation --}}
            <div class="wab-label-create-section">
                <div class="wab-section-label" style="padding:0 20px">Custom Labels</div>
                <div class="wab-label-create-row-wrap">
                    <div class="wab-label-color-preview" id="labelColorPreview" style="background:#00a884"></div>
                    <input type="text" id="newLabelName" class="wab-form-input" placeholder="Label name e.g. Hot Lead" style="flex:1">
                    <input type="color" id="newLabelColor" class="wab-color-input" value="#00a884" title="Pick color">
                    <button type="button" class="wab-btn wab-btn--primary" id="createLabelBtn" style="white-space:nowrap">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add
                    </button>
                </div>
                <div id="createLabelMsg" style="padding:0 20px 6px;font-size:12px;display:none"></div>
                <div class="wab-existing-labels-grid" id="existingLabelsGrid">
                    @forelse($labels as $label)
                        <div class="wab-exist-label-pill" data-id="{{ $label->id }}">
                            <span class="wab-exist-dot" style="background:{{ $label->color }}"></span>
                            <span class="wab-exist-name">{{ $label->name }}</span>
                        </div>
                    @empty
                        <span class="wab-no-labels-hint" id="noLabelsHint">No custom labels yet. Create one above.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     ASSIGN LABELS MODAL (Multiple Label Tagging + Cross Sync)
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waAssignLabelsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content wab-modal-content">
            <form method="POST" action="{{ route('whatsapp.chat.contact-labels.save') }}" id="waAssignLabelsForm">
                @csrf
                <input type="hidden" name="phone" id="waAssignPhoneInput" value="{{ $selectedPhone }}">
                <div class="modal-header wab-modal-header" style="background: linear-gradient(135deg, #3454d1, #1e3a8a);">
                    <div class="wab-modal-icon" style="background: rgba(255,255,255,0.2);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title wab-modal-title text-white">Assign Labels</h5>
                        <div class="text-white opacity-75 fs-8" id="waAssignContactSubtitle">Auto-synced with Email inbox</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body wab-modal-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                        <span class="fs-8 text-muted fw-bold text-uppercase">Select Labels</span>
                        <a href="{{ route('labels.index') }}" target="_blank" class="fs-9 text-primary fw-semibold"><i class="fa fa-cog"></i> Master</a>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        @forelse($labels as $label)
                            <label class="form-check form-check-custom form-check-solid d-flex align-items-center gap-2 p-2 rounded hover-bg-light cursor-pointer mb-0">
                                <input class="form-check-input wa-contact-label-modal-chk" type="checkbox" name="labels[{{ $label->id }}]" value="1" id="wa-chk-label-{{ $label->id }}" data-label-id="{{ $label->id }}" data-name="{{ $label->name }}" data-color="{{ $label->color }}" onchange="autoApplyWhatsAppModalLabels()" {{ in_array($label->id, $selectedContactLabels) ? 'checked' : '' }}>
                                <span class="badge px-2.5 py-1 fs-8 fw-bold" style="background-color: {{ $label->color }}; color: #ffffff;">
                                    {{ $label->name }}
                                </span>
                            </label>
                        @empty
                            <div class="text-center py-4 text-muted fs-8">
                                No labels found. <a href="{{ route('labels.index') }}" target="_blank">Create in Label Master</a>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer wab-modal-footer p-2">
                    <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="wab-btn wab-btn--primary">Save &amp; Sync</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     CREATE LEAD MODAL FROM WHATSAPP CONTACT
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waCreateLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <div class="modal-content wab-modal-content">
            <form method="POST" action="{{ route('whatsapp.chat.create-lead') }}">
                @csrf
                <input type="hidden" name="return_phone" value="{{ $selectedPhone }}">
                
                <div class="modal-header wab-modal-header" style="background:linear-gradient(135deg,#00a884,#008069)">
                    <div class="wab-modal-icon" style="background:rgba(255,255,255,0.2)">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7.5" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <div>
                        <h5 class="modal-title wab-modal-title text-white">Create New Lead from WhatsApp</h5>
                        <div class="text-white opacity-75 fs-8">Create CRM Lead &amp; Order directly for contact {{ $selectedPhone }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body wab-modal-body p-4">
                    <div class="row g-3">
                        {{-- Customer Info --}}
                        <div class="col-md-6">
                            <label class="wab-form-label">Customer Name</label>
                            <input type="text" name="user_name" class="wab-form-input" value="{{ $selectedContact['name'] ?? '' }}" placeholder="Customer Name">
                        </div>
                        <div class="col-md-6">
                            <label class="wab-form-label">Email Address</label>
                            <input type="email" name="email" class="wab-form-input" value="{{ $existingUser->email ?? '' }}" placeholder="customer@example.com (optional)">
                        </div>

                        {{-- Phone Numbers --}}
                        @php
                            $extractedCode = '+91';
                            $extractedMobile = $selectedPhone;
                            if ($selectedPhone) {
                                $cleanP = preg_replace('/\D+/', '', $selectedPhone);
                                if (str_starts_with($cleanP, '91') && strlen($cleanP) >= 12) {
                                    $extractedCode = '+91';
                                    $extractedMobile = substr($cleanP, 2);
                                } elseif (str_starts_with($cleanP, '44') && strlen($cleanP) >= 11) {
                                    $extractedCode = '+44';
                                    $extractedMobile = substr($cleanP, 2);
                                } elseif (str_starts_with($cleanP, '1') && strlen($cleanP) >= 11) {
                                    $extractedCode = '+1';
                                    $extractedMobile = substr($cleanP, 1);
                                } elseif (str_starts_with($cleanP, '971') && strlen($cleanP) >= 11) {
                                    $extractedCode = '+971';
                                    $extractedMobile = substr($cleanP, 3);
                                }
                            }
                        @endphp
                        <div class="col-md-4">
                            <label class="wab-form-label">Country Code *</label>
                            <select name="countrycode" class="wab-form-select" required>
                                <option value="+91" {{ $extractedCode === '+91' ? 'selected' : '' }}>🇮🇳 +91 (India)</option>
                                <option value="+44" {{ $extractedCode === '+44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                <option value="+1" {{ $extractedCode === '+1' ? 'selected' : '' }}>🇺🇸 +1 (US)</option>
                                <option value="+61" {{ $extractedCode === '+61' ? 'selected' : '' }}>🇦🇺 +61 (Australia)</option>
                                <option value="+971" {{ $extractedCode === '+971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                                <option value="+60" {{ $extractedCode === '+60' ? 'selected' : '' }}>🇲🇾 +60 (Malaysia)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="wab-form-label">Mobile Number *</label>
                            <input type="text" name="mobile" class="wab-form-input font-monospace" value="{{ $extractedMobile }}" required placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-md-4">
                            <label class="wab-form-label">Lead Source *</label>
                            <select name="lead_source" class="wab-form-select" required>
                                <option value="WhatsApp" selected>WhatsApp</option>
                                @foreach($sourcesList ?? [] as $src)
                                    <option value="{{ $src->source_name ?? $src->name }}">{{ $src->source_name ?? $src->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Project & Order Details --}}
                        <div class="col-md-8">
                            <label class="wab-form-label">Project / Assignment Title</label>
                            <input type="text" name="project_title" class="wab-form-input" placeholder="e.g. MBA Marketing Assignment" value="WhatsApp Chat Inquiry">
                        </div>
                        <div class="col-md-4">
                            <label class="wab-form-label">Module Code</label>
                            <input type="text" name="module_code" class="wab-form-input" placeholder="e.g. MKT-501">
                        </div>

                        <div class="col-md-4">
                            <label class="wab-form-label">Service Type</label>
                            <select name="service_type" class="wab-form-select">
                                <option value="">-- Select Service --</option>
                                @foreach($servicesList ?? [] as $serv)
                                    <option value="{{ $serv->service_name }}">{{ $serv->service_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="wab-form-label">Type of Paper</label>
                            <select name="paper" class="wab-form-select">
                                <option value="">-- Select Paper --</option>
                                @foreach($papersList ?? [] as $pap)
                                    <option value="{{ $pap->paper_type }}">{{ $pap->paper_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="wab-form-label">Lead Status</label>
                            <select name="i_status" class="wab-form-select">
                                <option value="Waiting" selected>Waiting</option>
                                <option value="Quote">Quote</option>
                                <option value="Confirmation">Confirmation</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="wab-form-label">Words / Pages</label>
                            <input type="number" name="pages" class="wab-form-input" placeholder="e.g. 2000" min="0" value="1000">
                        </div>
                        <div class="col-md-3">
                            <label class="wab-form-label">Price / Amount (£)</label>
                            <input type="number" name="amount" class="wab-form-input" placeholder="0.00" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="wab-form-label">Delivery Date</label>
                            <input type="date" name="delivery_date" class="wab-form-input" value="{{ now()->addDays(3)->toDateString() }}" min="{{ now()->toDateString() }}">
                        </div>
                        <div class="col-md-3">
                            <label class="wab-form-label">Delivery Time</label>
                            <input type="time" name="delivery_time" class="wab-form-input" value="18:00">
                        </div>

                        <div class="col-md-6 d-flex align-items-center gap-4 mt-3">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="tech" value="on" class="form-check-input">
                                <span class="fs-8 fw-bold">Technical</span>
                            </label>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="resit" value="on" class="form-check-input">
                                <span class="fs-8 fw-bold">Resit</span>
                            </label>
                        </div>

                        <div class="col-12">
                            <label class="wab-form-label">Requirements / Message</label>
                            <textarea name="message" rows="2" class="wab-form-input" placeholder="Customer instructions or chat notes...">Inquiry received via WhatsApp Chat.</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer wab-modal-footer">
                    <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="wab-btn wab-btn--primary" style="background:#00a884">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Create Lead &amp; Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.wab-crm-border-table {
    border: 1px solid #cbd5e1 !important;
    border-collapse: collapse !important;
    width: 100%;
}
.wab-crm-border-table th, .wab-crm-border-table td {
    border: 1px solid #cbd5e1 !important;
    padding: 8px 10px !important;
    vertical-align: middle !important;
}
.wab-crm-border-table thead th {
    background-color: #f1f5f9 !important;
    color: #1e293b !important;
    font-weight: 700 !important;
    border-bottom: 2px solid #94a3b8 !important;
}
.wab-crm-border-table tbody tr:hover {
    background-color: #f8fafc !important;
}
.wab-chat-preloader {
    position: absolute;
    inset: 0;
    background: rgba(240, 242, 245, 0.88);
    backdrop-filter: blur(3px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 100;
    transition: opacity 0.2s ease;
}
</style>

{{-- ══════════════════════════════════════════════════
     CHECK LEADS MODAL (AJAX Preloader & On-Demand Load)
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waCheckLeadsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-1000px modal-dialog-scrollable">
        <div class="modal-content wab-modal-content">
            <div class="modal-header wab-modal-header" style="background:linear-gradient(135deg,#1565c0,#0d47a1)">
                <div class="wab-modal-icon" style="background:rgba(255,255,255,0.2)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div class="d-flex flex-column">
                    <h5 class="modal-title wab-modal-title text-white mb-0">Customer Leads History</h5>
                    <div class="text-white opacity-75 fs-8">Customer: <strong>{{ $selectedContact['name'] ?? 'User' }}</strong> ({{ $selectedPhone }})</div>
                </div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-success py-1 px-3 fs-8 fw-bold" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#waCreateLeadModal">
                        <i class="fa fa-plus me-1"></i>+ New Lead
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-0" id="waLeadsScrollBody" style="max-height:460px;overflow-y:auto">
                {{-- Spinner / Preloader --}}
                <div id="waLeadsPreloader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width:2.5rem;height:2.5rem">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-3 text-dark fw-bold fs-7">Loading Leads Data...</div>
                    <div class="text-muted fs-8">Fetching customer leads from CRM...</div>
                </div>

                {{-- Empty State --}}
                <div id="waLeadsEmpty" class="text-center py-5 d-none">
                    <div class="text-muted fs-4 mb-2"><i class="fa fa-folder-open text-gray-400 fs-1"></i></div>
                    <div class="fw-bolder text-dark fs-6">No Leads Found for this Customer</div>
                    <div class="text-muted fs-8 mt-1">Is WhatsApp number ke sath abhi tak koi CRM Lead record nahi mila.</div>
                    <button type="button" class="btn btn-sm btn-primary mt-3" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#waCreateLeadModal">
                        <i class="fa fa-plus-circle me-1"></i>Create First Lead
                    </button>
                </div>

                {{-- Table Wrap --}}
                <div id="waLeadsTableWrap" class="table-responsive d-none">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0 fs-8 wab-crm-border-table" id="waLeadsTable" style="border:1px solid #cbd5e1 !important">
                        <thead class="bg-light fw-bolder text-uppercase text-gray-800 fs-9 position-sticky top-0" style="z-index:2;background:#f1f5f9 !important">
                            <tr>
                                <th style="border:1px solid #cbd5e1 !important;width:40px;text-align:center">#</th>
                                <th style="border:1px solid #cbd5e1 !important">Lead ID</th>
                                <th style="border:1px solid #cbd5e1 !important">Title &amp; Service</th>
                                <th style="border:1px solid #cbd5e1 !important;text-align:center">Words</th>
                                <th style="border:1px solid #cbd5e1 !important;min-width:130px">Total &amp; Due Amount</th>
                                <th style="border:1px solid #cbd5e1 !important;text-align:center">Status</th>
                                <th style="border:1px solid #cbd5e1 !important">Deadline</th>
                                <th style="border:1px solid #cbd5e1 !important;text-align:end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="waLeadsTbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer wab-modal-footer d-flex justify-content-between align-items-center">
                <div class="fs-8 text-muted">
                    Showing <strong id="waLeadsCountDisplay">0</strong> of <strong id="waLeadsTotalDisplay">0</strong> Leads
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" class="btn btn-xs btn-light-primary py-1 px-3 fs-8 d-none" id="waLoadMoreLeadsBtn">
                        <i class="fa fa-arrow-down me-1"></i>Load Next 10
                    </button>
                    <button type="button" class="wab-btn wab-btn--ghost py-1" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     CHECK ORDERS MODAL (AJAX Preloader & On-Demand Load)
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waCheckOrdersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-1000px modal-dialog-scrollable">
        <div class="modal-content wab-modal-content">
            <div class="modal-header wab-modal-header" style="background:linear-gradient(135deg,#6a1b9a,#4a148c)">
                <div class="wab-modal-icon" style="background:rgba(255,255,255,0.2)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
                <div class="d-flex flex-column">
                    <h5 class="modal-title wab-modal-title text-white mb-0">Customer Orders History</h5>
                    <div class="text-white opacity-75 fs-8">Customer: <strong>{{ $selectedContact['name'] ?? 'User' }}</strong> ({{ $selectedPhone }})</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="waOrdersScrollBody" style="max-height:460px;overflow-y:auto">
                {{-- Spinner / Preloader --}}
                <div id="waOrdersPreloader" class="text-center py-5">
                    <div class="spinner-border" role="status" style="width:2.5rem;height:2.5rem;color:#6a1b9a">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-3 text-dark fw-bold fs-7">Loading Orders Data...</div>
                    <div class="text-muted fs-8">Fetching customer orders &amp; payments from CRM...</div>
                </div>

                {{-- Empty State --}}
                <div id="waOrdersEmpty" class="text-center py-5 d-none">
                    <div class="text-muted fs-4 mb-2"><i class="fa fa-box-open text-gray-400 fs-1"></i></div>
                    <div class="fw-bolder text-dark fs-6">No Orders Found for this Customer</div>
                    <div class="text-muted fs-8 mt-1">Is customer ke liye abhi tak koi order record create nahi hua hai.</div>
                </div>

                {{-- Table Wrap --}}
                <div id="waOrdersTableWrap" class="table-responsive d-none">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0 fs-8 wab-crm-border-table" id="waOrdersTable" style="border:1px solid #cbd5e1 !important">
                        <thead class="bg-light fw-bolder text-uppercase text-gray-800 fs-9 position-sticky top-0" style="z-index:2;background:#f1f5f9 !important">
                            <tr>
                                <th style="border:1px solid #cbd5e1 !important;width:40px;text-align:center">#</th>
                                <th style="border:1px solid #cbd5e1 !important">Order ID</th>
                                <th style="border:1px solid #cbd5e1 !important">Title &amp; Service</th>
                                <th style="border:1px solid #cbd5e1 !important;text-align:center">Words</th>
                                <th style="border:1px solid #cbd5e1 !important;min-width:145px">Total, Paid &amp; Due Amount</th>
                                <th style="border:1px solid #cbd5e1 !important;text-align:center">Project Status</th>
                                <th style="border:1px solid #cbd5e1 !important">Delivery Date</th>
                                <th style="border:1px solid #cbd5e1 !important;text-align:end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="waOrdersTbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer wab-modal-footer d-flex justify-content-between align-items-center">
                <div class="fs-8 text-muted">
                    Showing <strong id="waOrdersCountDisplay">0</strong> of <strong id="waOrdersTotalDisplay">0</strong> Orders
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" class="btn btn-xs btn-light-primary py-1 px-3 fs-8 d-none" id="waLoadMoreOrdersBtn">
                        <i class="fa fa-arrow-down me-1"></i>Load Next 10
                    </button>
                    <button type="button" class="wab-btn wab-btn--ghost py-1" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.selectedCustomerPhone = @json($selectedPhone ?? '');
window.leadsLoadedPhone = null;
window.ordersLoadedPhone = null;
window.resetLeadsOrdersPhone = function(phone) {
    window.selectedCustomerPhone = phone;
    window.leadsLoadedPhone = null;
    window.ordersLoadedPhone = null;
};

document.addEventListener('DOMContentLoaded', function() {
    // ──────────────────────────────────────────────
    // 1. AJAX On-Demand Leads Loader
    // ──────────────────────────────────────────────
    let leadsCurrentPage = 1;
    let leadsTotal = 0;
    let leadsLoadedCount = 0;
    let leadsHasMore = false;
    let leadsIsLoading = false;

    const leadsModal = document.getElementById('waCheckLeadsModal');
    const leadsPreloader = document.getElementById('waLeadsPreloader');
    const leadsEmpty = document.getElementById('waLeadsEmpty');
    const leadsTableWrap = document.getElementById('waLeadsTableWrap');
    const leadsTbody = document.getElementById('waLeadsTbody');
    const leadsCountDisplay = document.getElementById('waLeadsCountDisplay');
    const leadsTotalDisplay = document.getElementById('waLeadsTotalDisplay');
    const loadMoreLeadsBtn = document.getElementById('waLoadMoreLeadsBtn');
    const leadsScrollBody = document.getElementById('waLeadsScrollBody');

    function fetchLeads(page = 1) {
        const phone = window.selectedCustomerPhone;
        if (!phone || leadsIsLoading) return;
        leadsIsLoading = true;

        if (page === 1) {
            leadsPreloader.classList.remove('d-none');
            leadsEmpty.classList.add('d-none');
            leadsTableWrap.classList.add('d-none');
            leadsTbody.innerHTML = '';
            leadsLoadedCount = 0;
        } else if (loadMoreLeadsBtn) {
            loadMoreLeadsBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
            loadMoreLeadsBtn.disabled = true;
        }

        fetch(`{{ route('whatsapp.chat.customer-leads') }}?phone=${encodeURIComponent(phone)}&page=${page}&limit=10`)
            .then(res => res.json())
            .then(data => {
                leadsIsLoading = false;
                leadsPreloader.classList.add('d-none');
                if (loadMoreLeadsBtn) {
                    loadMoreLeadsBtn.innerHTML = '<i class="fa fa-arrow-down me-1"></i>Load Next 10';
                    loadMoreLeadsBtn.disabled = false;
                }

                if (!data.success || !data.leads || data.leads.length === 0) {
                    if (page === 1) {
                        leadsEmpty.classList.remove('d-none');
                        leadsTableWrap.classList.add('d-none');
                        leadsCountDisplay.textContent = '0';
                        leadsTotalDisplay.textContent = '0';
                    }
                    leadsHasMore = false;
                    if (loadMoreLeadsBtn) loadMoreLeadsBtn.classList.add('d-none');
                    return;
                }

                leadsTotal = data.total;
                leadsHasMore = data.has_more;
                leadsCurrentPage = data.page;

                let rowsHtml = '';
                data.leads.forEach((lead) => {
                    leadsLoadedCount++;
                    rowsHtml += `
                        <tr class="wa-lead-row">
                            <td class="text-muted text-center" style="border:1px solid #cbd5e1 !important">${leadsLoadedCount}</td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <a href="${lead.edit_url}" target="_blank" class="fw-bolder text-primary text-hover-dark">
                                    #${lead.order_id}
                                </a>
                            </td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <div class="fw-bold text-dark text-truncate" style="max-width:220px" title="${lead.project_title}">
                                    ${lead.project_title}
                                </div>
                                <div class="text-muted fs-9">${lead.service_type}</div>
                            </td>
                            <td class="text-center" style="border:1px solid #cbd5e1 !important">${lead.pages}</td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <strong class="text-dark fs-9">£${lead.price_formatted}</strong>
                            </td>
                            <td class="text-center" style="border:1px solid #cbd5e1 !important">
                                <span class="badge ${lead.status_class} py-1 px-2">${lead.status}</span>
                            </td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <div>${lead.deadline}</div>
                                ${lead.delivery_time ? `<div class="text-muted fs-9">${lead.delivery_time}</div>` : ''}
                            </td>
                            <td class="text-end" style="border:1px solid #cbd5e1 !important">
                                <a href="${lead.edit_url}" target="_blank" class="btn btn-xs btn-light-primary py-1 px-2">
                                    <i class="fa fa-external-link me-1"></i>View
                                </a>
                            </td>
                        </tr>
                    `;
                });

                leadsTbody.insertAdjacentHTML('beforeend', rowsHtml);
                leadsTableWrap.classList.remove('d-none');
                leadsEmpty.classList.add('d-none');

                leadsCountDisplay.textContent = leadsLoadedCount;
                leadsTotalDisplay.textContent = leadsTotal;

                if (leadsHasMore && loadMoreLeadsBtn) {
                    loadMoreLeadsBtn.classList.remove('d-none');
                } else if (loadMoreLeadsBtn) {
                    loadMoreLeadsBtn.classList.add('d-none');
                }
            })
            .catch(err => {
                leadsIsLoading = false;
                leadsPreloader.classList.add('d-none');
                console.error('Error fetching customer leads:', err);
            });
    }

    if (leadsModal) {
        leadsModal.addEventListener('show.bs.modal', function() {
            if (window.leadsLoadedPhone !== window.selectedCustomerPhone) {
                window.leadsLoadedPhone = window.selectedCustomerPhone;
                fetchLeads(1);
            }
        });
    }

    if (loadMoreLeadsBtn) {
        loadMoreLeadsBtn.addEventListener('click', function() {
            if (leadsHasMore && !leadsIsLoading) {
                fetchLeads(leadsCurrentPage + 1);
            }
        });
    }

    if (leadsScrollBody) {
        leadsScrollBody.addEventListener('scroll', function() {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight - 30) {
                if (leadsHasMore && !leadsIsLoading) {
                    fetchLeads(leadsCurrentPage + 1);
                }
            }
        });
    }

    // ──────────────────────────────────────────────
    // 2. AJAX On-Demand Orders Loader
    // ──────────────────────────────────────────────
    let ordersCurrentPage = 1;
    let ordersTotal = 0;
    let ordersLoadedCount = 0;
    let ordersHasMore = false;
    let ordersIsLoading = false;

    const ordersModal = document.getElementById('waCheckOrdersModal');
    const ordersPreloader = document.getElementById('waOrdersPreloader');
    const ordersEmpty = document.getElementById('waOrdersEmpty');
    const ordersTableWrap = document.getElementById('waOrdersTableWrap');
    const ordersTbody = document.getElementById('waOrdersTbody');
    const ordersCountDisplay = document.getElementById('waOrdersCountDisplay');
    const ordersTotalDisplay = document.getElementById('waOrdersTotalDisplay');
    const loadMoreOrdersBtn = document.getElementById('waLoadMoreOrdersBtn');
    const ordersScrollBody = document.getElementById('waOrdersScrollBody');

    function fetchOrders(page = 1) {
        const phone = window.selectedCustomerPhone;
        if (!phone || ordersIsLoading) return;
        ordersIsLoading = true;

        if (page === 1) {
            ordersPreloader.classList.remove('d-none');
            ordersEmpty.classList.add('d-none');
            ordersTableWrap.classList.add('d-none');
            ordersTbody.innerHTML = '';
            ordersLoadedCount = 0;
        } else if (loadMoreOrdersBtn) {
            loadMoreOrdersBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
            loadMoreOrdersBtn.disabled = true;
        }

        fetch(`{{ route('whatsapp.chat.customer-orders') }}?phone=${encodeURIComponent(phone)}&page=${page}&limit=10`)
            .then(res => res.json())
            .then(data => {
                ordersIsLoading = false;
                ordersPreloader.classList.add('d-none');
                if (loadMoreOrdersBtn) {
                    loadMoreOrdersBtn.innerHTML = '<i class="fa fa-arrow-down me-1"></i>Load Next 10';
                    loadMoreOrdersBtn.disabled = false;
                }

                if (!data.success || !data.orders || data.orders.length === 0) {
                    if (page === 1) {
                        ordersEmpty.classList.remove('d-none');
                        ordersTableWrap.classList.add('d-none');
                        ordersCountDisplay.textContent = '0';
                        ordersTotalDisplay.textContent = '0';
                    }
                    ordersHasMore = false;
                    if (loadMoreOrdersBtn) loadMoreOrdersBtn.classList.add('d-none');
                    return;
                }

                ordersTotal = data.total;
                ordersHasMore = data.has_more;
                ordersCurrentPage = data.page;

                let rowsHtml = '';
                data.orders.forEach((ord) => {
                    ordersLoadedCount++;
                    const dueBadge = ord.due_amount > 0
                        ? `<span class="badge badge-light-danger fw-bold fs-9 py-0 px-1">£${ord.due_amount_formatted}</span>`
                        : `<span class="badge badge-light-success fw-bold fs-9 py-0 px-1">£0.00 (Paid)</span>`;
                    const paidRow = ord.received_amount > 0
                        ? `<div class="d-flex justify-content-between align-items-center"><span class="text-muted fs-9">Paid:</span><span class="text-success fw-bold fs-9">£${ord.received_amount_formatted}</span></div>`
                        : '';

                    rowsHtml += `
                        <tr class="wa-order-row">
                            <td class="text-muted text-center" style="border:1px solid #cbd5e1 !important">${ordersLoadedCount}</td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <a href="${ord.edit_url}" target="_blank" class="fw-bolder text-primary text-hover-dark">
                                    #${ord.order_id}
                                </a>
                            </td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <div class="fw-bold text-dark text-truncate" style="max-width:220px" title="${ord.title}">
                                    ${ord.title}
                                </div>
                                <div class="text-muted fs-9">${ord.service_type}</div>
                            </td>
                            <td class="text-center" style="border:1px solid #cbd5e1 !important">${ord.pages}</td>
                            <td style="border:1px solid #cbd5e1 !important">
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted fs-9">Total:</span>
                                        <strong class="text-dark fs-9">£${ord.total_amount_formatted}</strong>
                                    </div>
                                    ${paidRow}
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted fs-9">Due:</span>
                                        ${dueBadge}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center" style="border:1px solid #cbd5e1 !important">
                                <span class="badge ${ord.status_class} py-1 px-2">${ord.status}</span>
                            </td>
                            <td style="border:1px solid #cbd5e1 !important">${ord.delivery_date}</td>
                            <td class="text-end" style="border:1px solid #cbd5e1 !important">
                                <a href="${ord.edit_url}" target="_blank" class="btn btn-xs btn-light-primary py-1 px-2">
                                    <i class="fa fa-external-link me-1"></i>View
                                </a>
                            </td>
                        </tr>
                    `;
                });

                ordersTbody.insertAdjacentHTML('beforeend', rowsHtml);
                ordersTableWrap.classList.remove('d-none');
                ordersEmpty.classList.add('d-none');

                ordersCountDisplay.textContent = ordersLoadedCount;
                ordersTotalDisplay.textContent = ordersTotal;

                if (ordersHasMore && loadMoreOrdersBtn) {
                    loadMoreOrdersBtn.classList.remove('d-none');
                } else if (loadMoreOrdersBtn) {
                    loadMoreOrdersBtn.classList.add('d-none');
                }
            })
            .catch(err => {
                ordersIsLoading = false;
                ordersPreloader.classList.add('d-none');
                console.error('Error fetching customer orders:', err);
            });
    }

    if (ordersModal) {
        ordersModal.addEventListener('show.bs.modal', function() {
            if (window.ordersLoadedPhone !== window.selectedCustomerPhone) {
                window.ordersLoadedPhone = window.selectedCustomerPhone;
                fetchOrders(1);
            }
        });
    }

    if (loadMoreOrdersBtn) {
        loadMoreOrdersBtn.addEventListener('click', function() {
            if (ordersHasMore && !ordersIsLoading) {
                fetchOrders(ordersCurrentPage + 1);
            }
        });
    }

    if (ordersScrollBody) {
        ordersScrollBody.addEventListener('scroll', function() {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight - 30) {
                if (ordersHasMore && !ordersIsLoading) {
                    fetchOrders(ordersCurrentPage + 1);
                }
            }
        });
    }
});
</script>

{{-- ══════════════════════════════════════════════════
     LABEL CHAT MODAL (WhatsApp-style tap-to-toggle)
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="waAssignLabelsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content wab-modal-content">
            <div class="modal-header wab-modal-header">
                <div class="wab-modal-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
                </div>
                <h5 class="modal-title wab-modal-title">Label Chat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body wab-modal-body" style="padding:16px">
                <div class="wab-label-search-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="labelSearchInput" placeholder="Search labels…">
                </div>
                <div class="wab-wa-label-list" id="waLabelList">
                    @forelse($labels as $label)
                        @php $isActive = in_array($label->id, $selectedContactLabels, true); @endphp
                        <div class="wab-wa-label-row {{ $isActive ? 'is-selected' : '' }}"
                             data-label-id="{{ $label->id }}"
                             data-label-name="{{ $label->name }}"
                             data-label-color="{{ $label->color }}"
                             onclick="toggleLabel(this)">
                            <div class="wab-wa-label-left">
                                <span class="wab-wa-label-icon" style="background:{{ $label->color }}">{{ strtoupper(substr($label->name,0,1)) }}</span>
                                <span class="wab-wa-label-name">{{ $label->name }}</span>
                            </div>
                            <span class="wab-wa-check" style="border-color:{{ $label->color }}">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="{{ $label->color }}" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                        </div>
                    @empty
                        <div class="wab-empty-labels" id="noLabelsTxt">No labels yet. Create labels from ⚙️ settings.</div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer wab-modal-footer" style="justify-content:space-between">
                <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="wab-btn wab-btn--primary" id="saveLabelAssignBtn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Labels
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── RIGHT PROFILE PANEL (WhatsApp-style sliding panel) ── --}}
<div class="wab-profile-panel" id="wabProfilePanel">
    <div class="wab-profile-panel-inner">
        {{-- Header --}}
        <div class="wab-pp-header">
            <button class="wab-icon-btn" id="wabCloseProfilePanel" title="Close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <span>Contact Info</span>
        </div>
        {{-- Avatar / Name --}}
        <div class="wab-pp-hero">
            <div class="wab-pp-avatar" style="background:{{ $selectedColor }}1a;color:{{ $selectedColor }}">{{ strtoupper(substr($selectedName,0,1)) }}</div>
            <div class="wab-pp-name">{{ $selectedName }}</div>
            <div class="wab-pp-phone">{{ $selectedPhone ?: 'N/A' }}</div>
            <div class="wab-pp-biz"><span class="wab-online-dot"></span> Open until 6:00 PM</div>
        </div>
        {{-- Actions row --}}
        <div class="wab-pp-actions">
            @if($selectedPhone)
            <button class="wab-pp-action-btn" data-bs-toggle="modal" data-bs-target="#waAssignLabelsModal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Label chat
            </button>
            @endif
        </div>
        {{-- Labels section --}}
        <div class="wab-pp-section">
            <div class="wab-pp-section-title">Labels</div>
            <div class="wab-pp-label-list">
                @forelse($activeLabels as $label)
                    <span class="wab-pp-label-tag" style="background:{{ $label->color }}1a;color:{{ $label->color }};border:1.5px solid {{ $label->color }}50">
                        <span class="wab-pp-label-dot" style="background:{{ $label->color }}"></span>
                        {{ $label->name }}
                    </span>
                @empty
                    <span class="wab-pp-no-labels">No labels assigned. Click "Label chat" to add.</span>
                @endforelse
            </div>
        </div>
        {{-- Stats section --}}
        <div class="wab-pp-section">
            <div class="wab-pp-section-title">Overview</div>
            <div class="wab-pp-stat-row"><span>Phone</span><strong>{{ $selectedPhone ?: 'N/A' }}</strong></div>
            <div class="wab-pp-stat-row"><span>Total Messages</span><strong>{{ $messages->count() }}</strong></div>
            <div class="wab-pp-stat-row"><span>Unread</span><strong>{{ $messages->where('direction','inbound')->whereNotIn('status',['read'])->count() }}</strong></div>
            <div class="wab-pp-stat-row"><span>Source</span><strong>WhatsApp Business</strong></div>
        </div>
        {{-- Notes section --}}
        <div class="wab-pp-section">
            <div class="wab-pp-section-title">Notes</div>
            <div class="wab-pp-notes-box">Add notes about your customer…</div>
        </div>
    </div>
</div>
<div class="wab-pp-overlay" id="wabProfileOverlay"></div>

<style>
/* ── Google Font ── */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ── CSS Variables ── */
:root {
    --wa-green:       #25d366;
    --wa-green-dark:  #128c7e;
    --wa-teal:        #00a884;
    --wa-bg-body:     #ece5dd;
    --wa-bg-sidebar:  #ffffff;
    --wa-bg-header:   #f0f2f5;
    --wa-border:      #e9edef;
    --wa-border-dark: #d1d7db;
    --wa-text-main:   #111b21;
    --wa-text-muted:  #667781;
    --wa-text-sub:    #8696a0;
    --wa-bubble-in:   #ffffff;
    --wa-bubble-out:  #d9fdd3;
    --wa-active:      #e9edef;
    --wa-hover:       #f5f6f6;
    --wa-shadow:      0 2px 24px rgba(17,27,33,.12);
    --wa-radius:      10px;
    --wa-font:        'Inter', system-ui, -apple-system, sans-serif;
    --sidebar-w:      390px;
    --header-h:       64px;
    --footer-h:       66px;
}

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ── Page Shell ── */
.wab-page {
    font-family: var(--wa-font);
    display: grid;
    grid-template-columns: var(--sidebar-w) 1fr;
    height: calc(100vh - 110px);
    margin-top: -70px;
    border-radius: var(--wa-radius);
    overflow: hidden;
    box-shadow: var(--wa-shadow);
    border: 1px solid var(--wa-border-dark);
    transition: grid-template-columns .22s cubic-bezier(.4,0,.2,1);
    position: relative;
}

.wab-page.sidebar-collapsed {
    grid-template-columns: 72px 1fr !important;
}

.sidebar-collapsed .wab-sidebar-header-left,
.sidebar-collapsed .wab-sidebar-title,
.sidebar-collapsed .wab-search-wrap,
.sidebar-collapsed .wab-tabs,
.sidebar-collapsed .wab-contact-info,
.sidebar-collapsed .wab-contact-tag-chip,
.sidebar-collapsed .wab-contact-label-tags,
.sidebar-collapsed .wab-header-actions button:not(#wabToggleSidebar) {
    display: none !important;
}

.sidebar-collapsed .wab-sidebar-header {
    justify-content: center !important;
    padding: 0 4px !important;
}

.sidebar-collapsed .wab-header-actions {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    width: 100% !important;
}

.sidebar-collapsed #wabToggleSidebar {
    display: inline-flex !important;
    margin: 0 auto !important;
}

.sidebar-collapsed .wab-contact-item {
    justify-content: center !important;
    padding: 10px 0 !important;
}

.sidebar-collapsed .wab-avatar {
    margin: 0 auto;
}

/* ════════════════════════════════════════
   SIDEBAR
════════════════════════════════════════ */
.wab-sidebar {
    display: flex;
    flex-direction: column;
    background: var(--wa-bg-sidebar);
    border-right: 1px solid var(--wa-border-dark);
    min-width: 0;
    overflow: hidden;
    position: relative;
    height: 100%;
}

/* ── Sidebar Header ── */
.wab-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: var(--header-h);
    padding: 0 16px;
    background: var(--wa-bg-header);
    border-bottom: 1px solid var(--wa-border);
    gap: 10px;
    flex-shrink: 0;
}
.wab-sidebar-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
.wab-sidebar-title { display: flex; flex-direction: column; min-width: 0; }
.wab-label-main { font-weight: 700; font-size: 16px; color: var(--wa-text-main); white-space: nowrap; }
.wab-label-sub  { font-size: 11px; color: var(--wa-text-muted); display: flex; align-items: center; gap: 5px; white-space: nowrap; }

.wab-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ── Avatar ── */
.wab-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
    position: relative;
    background: #dfe5e71a;
    color: #54656f;
    letter-spacing: .5px;
    transition: transform .15s ease;
}
.wab-avatar--header { width: 38px; height: 38px; font-size: 14px; }
.wab-avatar--conv   { width: 44px; height: 44px; font-size: 17px; }

/* ── Status Badge ── */
.wab-status-badge {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    border: 2px solid #fff;
}
.wab-status--online  { background: var(--wa-green); }
.wab-status--away    { background: #ffc107; }
.wab-status--offline { background: #bec8cf; }

/* ── Online dot ── */
.wab-online-dot {
    width: 7px;
    height: 7px;
    display: inline-block;
    border-radius: 50%;
    background: var(--wa-green);
    box-shadow: 0 0 0 3px rgba(37,211,102,.2);
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%,100% { box-shadow: 0 0 0 3px rgba(37,211,102,.2); }
    50%      { box-shadow: 0 0 0 6px rgba(37,211,102,.08); }
}

/* ── Icon Button ── */
.wab-icon-btn {
    width: 36px; height: 36px;
    display: inline-flex; align-items: center; justify-content: center;
    border: 0; border-radius: 50%; background: transparent;
    color: var(--wa-text-muted); cursor: pointer;
    transition: background .14s ease, color .14s ease, transform .14s ease;
    flex-shrink: 0;
}
.wab-icon-btn:hover {
    background: rgba(84,101,111,.12);
    color: var(--wa-text-main);
    transform: scale(1.08);
}
.wab-header-actions { display: flex; align-items: center; gap: 2px; }

/* ── Search ── */
.wab-search-wrap {
    padding: 8px 12px;
    background: #fff;
    border-bottom: 1px solid var(--wa-border);
    flex-shrink: 0;
}
.wab-search-box {
    height: 38px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 14px;
    border-radius: 20px;
    background: var(--wa-bg-header);
    transition: box-shadow .15s;
}
.wab-search-box:focus-within {
    box-shadow: 0 0 0 2px rgba(37,211,102,.25);
}
.wab-search-icon { color: var(--wa-text-muted); flex-shrink: 0; }
.wab-search-box input {
    flex: 1; border: 0; outline: 0;
    background: transparent;
    font-size: 14px;
    font-family: var(--wa-font);
    color: var(--wa-text-main);
}
.wab-search-box input::placeholder { color: var(--wa-text-sub); }

/* ── Tabs ── */
.wab-tabs {
    display: flex;
    gap: 4px;
    padding: 6px 12px;
    border-bottom: 1px solid var(--wa-border);
    background: #fff;
    flex-shrink: 0;
}
.wab-tab {
    flex: 1; padding: 5px 0;
    border: 0; border-radius: 20px;
    background: transparent;
    font-size: 12px; font-weight: 500; font-family: var(--wa-font);
    color: var(--wa-text-muted); cursor: pointer;
    transition: background .15s, color .15s;
}
.wab-tab.active {
    background: #e7fce3;
    color: #008069;
    font-weight: 700;
}
.wab-tab:hover:not(.active) {
    background: rgba(11,20,26,.05);
    color: var(--wa-text-main);
}
.wab-conv-toggle-sidebar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 6px;
    color: var(--wa-icon);
    cursor: pointer;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    transition: background-color .15s;
}
.wab-conv-toggle-sidebar:hover {
    background: rgba(11,20,26,.08);
}

/* ── Contact List ── */
.wab-contact-list {
    flex: 1 1 auto;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #d1d7db transparent;
}
.wab-contact-list::-webkit-scrollbar { width: 5px; }
.wab-contact-list::-webkit-scrollbar-thumb { background: #d1d7db; border-radius: 10px; }

.wab-contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px 12px 18px;
    border-bottom: 1px solid var(--wa-border);
    cursor: pointer;
    transition: background .12s ease;
    position: relative;
}
.wab-contact-item:hover { background: var(--wa-hover); }
.wab-contact-item.is-active { background: var(--wa-active); }
.wab-contact-item.is-active::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--wa-green);
    border-radius: 0 3px 3px 0;
}

.wab-contact-info { flex: 1; min-width: 0; }
.wab-contact-row-top, .wab-contact-row-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.wab-contact-row-bottom { margin-top: 3px; }
.wab-contact-name    { font-size: 14px; font-weight: 600; color: var(--wa-text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 210px; }
.wab-contact-time    { font-size: 11px; color: var(--wa-text-sub); white-space: nowrap; }
.wab-contact-preview { font-size: 12.5px; color: var(--wa-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 210px; }
.wab-badge {
    min-width: 18px; height: 18px;
    border-radius: 9px;
    background: var(--wa-green);
    color: #fff;
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    padding: 0 4px;
    flex-shrink: 0;
    animation: badge-pop .2s ease;
}
.wab-quick-tag-btn {
    width: 22px;
    height: 22px;
    border-radius: 5px;
    border: 1px solid var(--wa-border);
    background: #ffffff;
    color: var(--wa-text-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .15s ease;
    flex-shrink: 0;
}
.wab-quick-tag-btn:hover {
    background: #e0f2fe;
    color: #0284c7;
    border-color: #bae6fd;
    transform: scale(1.1);
}
@keyframes badge-pop { 0% { transform: scale(0); } 80% { transform: scale(1.15); } 100% { transform: scale(1); } }

/* ════════════════════════════════════════
   CONVERSATION PANEL
════════════════════════════════════════ */
.wab-conversation {
    display: grid;
    grid-template-rows: var(--header-h) 1fr var(--footer-h);
    min-width: 0;
    min-height: 0;
    position: relative;
    overflow: hidden;
}

/* ── Background pattern ── */
.wab-messages-body {
    background-color: #ece5dd;
    background-image:
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='1' fill='rgba(0,0,0,0.04)'/%3E%3C/svg%3E");
    padding: 18px 5%;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #c1c8cc transparent;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.wab-messages-body::-webkit-scrollbar { width: 5px; }
.wab-messages-body::-webkit-scrollbar-thumb { background: #c1c8cc; border-radius: 10px; }

/* ── Conv Header ── */
.wab-conv-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 18px;
    background: var(--wa-bg-header);
    border-bottom: 1px solid var(--wa-border-dark);
    gap: 12px;
}
.wab-conv-header-left { display: flex; align-items: center; gap: 10px; }
.wab-conv-name   { font-size: 15px; font-weight: 700; color: var(--wa-text-main); }
.wab-conv-status { font-size: 12px; color: var(--wa-text-muted); display: flex; align-items: center; gap: 5px; margin-top: 1px; }
.wab-conv-actions { display: flex; align-items: center; gap: 2px; }
.wab-mobile-back { display: none; }
.wab-chat-more {
    position: relative;
}
.wab-chat-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 170px;
    display: none;
    padding: 6px;
    border-radius: 8px;
    background: #fff;
    border: 1px solid var(--wa-border-dark);
    box-shadow: 0 8px 28px rgba(17,27,33,.18);
    z-index: 30;
}
.wab-chat-menu.is-open {
    display: grid;
    gap: 2px;
}
.wab-chat-menu button,
.wab-chat-menu a {
    width: 100%;
    border: 0;
    background: transparent;
    color: var(--wa-text-main);
    text-align: left;
    padding: 9px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}
.wab-chat-menu button:hover,
.wab-chat-menu a:hover {
    background: var(--wa-hover);
}
.wab-blank-chat {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f2f5;
}
.wab-blank-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 68px;
    color: var(--wa-text-main);
}
.wab-blank-actions button {
    display: grid;
    justify-items: center;
    gap: 14px;
    border: 0;
    background: transparent;
    color: var(--wa-text-main);
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
}
.wab-blank-actions svg {
    width: 64px;
    height: 64px;
    padding: 18px;
    border-radius: 50%;
    background: rgba(84,101,111,.12);
    color: var(--wa-text-muted);
}

/* ── Date Badge ── */
.wab-date-badge {
    align-self: center;
    padding: 5px 14px;
    border-radius: 20px;
    background: rgba(255,255,255,.88);
    color: var(--wa-text-sub);
    font-size: 11.5px;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(17,27,33,.1);
    margin-bottom: 6px;
    letter-spacing: .3px;
}

/* ── Message Row ── */
.wab-msg-row {
    display: flex;
    flex-direction: column;
    max-width: 65%;
    margin-bottom: 6px;
    animation: msg-in .22s ease;
}
@keyframes msg-in {
    from { opacity: 0; transform: translateY(10px) scale(.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.wab-incoming { align-self: flex-start; align-items: flex-start; }
.wab-outgoing { align-self: flex-end;   align-items: flex-end;   }

/* ── Bubble ── */
.wab-msg-bubble {
    position: relative;
    padding: 9px 12px 28px;
    border-radius: 8px;
    font-size: 14px;
    line-height: 1.5;
    color: var(--wa-text-main);
    box-shadow: 0 1px 2px rgba(17,27,33,.12);
    word-break: break-word;
    min-width: 80px;
}
.wab-incoming .wab-msg-bubble {
    background: var(--wa-bubble-in);
    border-radius: 0 8px 8px 8px;
}
.wab-outgoing .wab-msg-bubble {
    background: var(--wa-bubble-out);
    border-radius: 8px 8px 0 8px;
}

/* Tail */
.wab-incoming .wab-msg-bubble::before {
    content: '';
    position: absolute;
    top: 0; left: -7px;
    border: 7px solid transparent;
    border-top-color: #fff;
    border-right: 0;
}
.wab-outgoing .wab-msg-bubble::before {
    content: '';
    position: absolute;
    top: 0; right: -7px;
    border: 7px solid transparent;
    border-top-color: #d9fdd3;
    border-left: 0;
}

/* ── Message Meta ── */
.wab-msg-meta {
    position: absolute;
    bottom: 6px; right: 10px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.wab-msg-time { font-size: 11px; color: var(--wa-text-sub); }
.wab-tick { display: flex; align-items: center; }
.wab-link { color: #0066cc; text-decoration: underline; }

.wab-bubble--media {
    padding: 5px 5px 28px;
    min-width: 180px;
    max-width: 330px;
}
.wab-bubble--audio {
    padding: 0 0 24px;
    background: transparent !important;
    box-shadow: none;
    min-width: 292px;
    max-width: 360px;
}
.wab-bubble--audio::before {
    display: none;
}
.wab-media-img,
.wab-media-video {
    display: block;
    width: 100%;
    max-width: 320px;
    max-height: 340px;
    border-radius: 6px;
    object-fit: cover;
    background: rgba(0,0,0,.06);
}
.wab-voice-card {
    min-width: 292px;
    max-width: 360px;
    height: 64px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 16px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(17,27,33,.18);
}
.wab-incoming .wab-voice-card {
    background: #fff;
}
.wab-outgoing .wab-voice-card {
    background: #fff;
}
.wab-voice-play {
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 50%;
    display: grid;
    place-items: center;
    flex: 0 0 auto;
    cursor: pointer;
    color: #fff;
    background: var(--wa-teal);
}
.wab-voice-wave {
    flex: 1;
    height: 32px;
    display: flex;
    align-items: center;
    gap: 3px;
    overflow: hidden;
}
.wab-voice-wave span {
    width: 4px;
    border-radius: 4px;
    background: #9aa8ae;
}
.wab-voice-card.is-playing .wab-voice-wave span {
    background: #25d366;
}
.wab-voice-time {
    min-width: 42px;
    font-size: 20px;
    font-weight: 700;
    color: var(--wa-text-main);
}
.wab-voice-audio {
    display: none;
}
.wab-media-caption {
    padding: 7px 7px 0;
    white-space: pre-wrap;
}
.wab-media-doc-card {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 260px;
    max-width: 320px;
    padding: 10px;
    border-radius: 7px;
    background: rgba(255,255,255,.72);
    color: var(--wa-text-main);
    text-decoration: none;
}
.wab-outgoing .wab-media-doc-card { background: rgba(255,255,255,.58); }
.wab-media-doc-card:hover { text-decoration: none; background: rgba(255,255,255,.9); }
.wab-media-doc-icon {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    position: relative;
    flex: 0 0 auto;
    background: #fff;
    border: 1px solid var(--wa-border-dark);
}
.wab-doc-ext {
    position: absolute;
    bottom: 3px;
    font-size: 8px;
    font-weight: 800;
}
.wab-media-doc-info {
    flex: 1;
    min-width: 0;
    display: grid;
    gap: 2px;
}
.wab-media-doc-name {
    font-size: 13px;
    font-weight: 700;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.wab-media-doc-size {
    font-size: 11px;
    color: var(--wa-text-sub);
}
.wab-doc-dl { flex: 0 0 auto; }
.wab-icon-btn.is-loading {
    opacity: .55;
    pointer-events: none;
}

/* ── Typing indicator ── */
.wab-typing-row { margin-bottom: 10px; display: none; }
.wab-typing-row.is-visible { display: flex; }
.wab-typing-bubble {
    padding: 12px 16px 12px !important;
    display: flex;
    align-items: center;
    gap: 5px;
    min-width: 56px;
}
.wab-typing-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--wa-text-sub);
    display: inline-block;
    animation: typing-bounce 1.4s infinite ease-in-out;
}
.wab-typing-dot:nth-child(1) { animation-delay: 0s; }
.wab-typing-dot:nth-child(2) { animation-delay: .2s; }
.wab-typing-dot:nth-child(3) { animation-delay: .4s; }
@keyframes typing-bounce {
    0%,60%,100% { transform: translateY(0);   opacity: .5; }
    30%          { transform: translateY(-6px); opacity: 1; }
}

/* ── Conv Footer ── */
.wab-conv-footer {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 16px;
    background: var(--wa-bg-header);
    border-top: 1px solid var(--wa-border-dark);
    position: relative;
}
.wab-footer-actions-left { display: flex; align-items: center; gap: 2px; position: relative; }
.wab-plus-btn {
    color: #54656f;
    transition: background .15s, color .15s, transform .15s;
}
.wab-plus-btn.is-open {
    background: #d9fdd3;
    color: var(--wa-teal);
    transform: rotate(45deg);
}
.wab-attach-menu {
    position: absolute;
    left: 2px;
    bottom: calc(100% + 12px);
    min-width: 248px;
    padding: 10px 0;
    border-radius: 12px;
    background: #111b21;
    box-shadow: 0 12px 34px rgba(17,27,33,.35);
    border: 1px solid rgba(255,255,255,.08);
    display: none;
    z-index: 140;
    overflow: hidden;
}
.wab-attach-menu.is-open { display: block; animation: panel-in .16s ease; }
.wab-attach-menu button {
    width: 100%;
    height: 48px;
    padding: 0 18px;
    border: 0;
    background: transparent;
    color: #e9edef;
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 15px;
    font-weight: 650;
    text-align: left;
    cursor: pointer;
}
.wab-attach-menu button:hover { background: rgba(255,255,255,.07); }
.wab-attach-icon {
    width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}
.wab-attach-icon--document { color: #7c4dff; }
.wab-attach-icon--media { color: #00a8ff; }
.wab-input-wrap { flex: 1; }
.wab-input {
    width: 100%; height: 44px;
    border: 0; outline: 0;
    border-radius: 22px;
    background: #fff;
    padding: 0 18px;
    font-size: 14px;
    font-family: var(--wa-font);
    color: var(--wa-text-main);
    transition: box-shadow .15s;
}
.wab-input:focus { box-shadow: 0 0 0 2px rgba(37,211,102,.2); }
.wab-input::placeholder { color: var(--wa-text-sub); }

.wab-send-btn {
    width: 44px; height: 44px;
    border: 0; border-radius: 50%;
    background: var(--wa-teal);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .15s, transform .15s, box-shadow .15s;
    box-shadow: 0 2px 8px rgba(0,168,132,.35);
}
.wab-send-btn:hover {
    background: var(--wa-green-dark);
    transform: scale(1.06);
    box-shadow: 0 4px 14px rgba(0,168,132,.45);
}
.wab-send-btn:active { transform: scale(.94); }
.wab-mic-btn {
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 50%;
    background: transparent;
    color: #54656f;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .15s, color .15s, transform .15s;
}
.wab-mic-btn:hover {
    background: rgba(0,0,0,.06);
    color: var(--wa-teal);
}
.wab-mic-btn.is-recording {
    background: #ffe4e8;
    color: #e11d48;
    animation: mic-pulse 1s infinite ease-in-out;
}
.wab-mic-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
}
.wab-audio-review {
    flex: 1;
    height: 48px;
    min-width: 0;
    border-radius: 24px;
    background: #202c33;
    color: #e9edef;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 0 14px;
}
.wab-conv-footer.is-audio-ready .wab-input-wrap,
.wab-conv-footer.is-audio-recording .wab-input-wrap,
.wab-conv-footer.is-audio-ready .wab-footer-actions-left,
.wab-conv-footer.is-audio-recording .wab-footer-actions-left {
    display: none;
}
.wab-conv-footer.is-audio-ready .wab-send-btn {
    display: flex;
}
.wab-conv-footer:not(.is-audio-ready) .wab-send-btn {
    display: flex;
}
.wab-audio-icon-btn {
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 50%;
    color: #f1f5f9;
    background: transparent;
    display: grid;
    place-items: center;
    cursor: pointer;
}
.wab-audio-icon-btn:hover {
    background: rgba(255,255,255,.08);
}
.wab-audio-wave {
    flex: 1;
    min-width: 80px;
    height: 28px;
    display: flex;
    align-items: center;
    gap: 3px;
    overflow: hidden;
}
.wab-audio-wave span {
    width: 3px;
    border-radius: 4px;
    background: #5f6b72;
}
.wab-audio-wave.is-playing span {
    background: #25d366;
}
.wab-audio-time {
    min-width: 44px;
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 0;
    color: #f8fafc;
}
@keyframes mic-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(225,29,72,.25); }
    50% { box-shadow: 0 0 0 8px rgba(225,29,72,0); }
}

/* ── Emoji Panel ── */
.wab-emoji-panel {
    position: absolute;
    bottom: calc(var(--footer-h) + 8px);
    left: 16px;
    width: 280px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 28px rgba(17,27,33,.18);
    border: 1px solid var(--wa-border);
    padding: 10px;
    display: none;
    flex-wrap: wrap;
    gap: 4px;
    z-index: 100;
    animation: panel-in .18s ease;
}
.wab-emoji-panel.is-open { display: flex; }
@keyframes panel-in {
    from { opacity: 0; transform: translateY(8px) scale(.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.wab-emoji-item {
    width: 36px; height: 36px;
    border: 0; border-radius: 8px;
    background: transparent;
    font-size: 20px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .12s, transform .12s;
}
.wab-emoji-item:hover { background: var(--wa-bg-header); transform: scale(1.2); }

.wab-upload-review {
    position: absolute;
    inset: 0;
    z-index: 180;
    background: #f0f2f5;
    color: #111b21;
    display: grid;
    grid-template-rows: 64px 1fr 88px 132px;
}
.wab-upload-review[hidden] { display: none; }
.wab-upload-review-head {
    display: grid;
    grid-template-columns: 52px 1fr 52px;
    align-items: center;
    min-width: 0;
    background: #ffffff;
    border-bottom: 1px solid #d1d7db;
}
.wab-upload-review-head strong {
    grid-column: 2;
    text-align: center;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 15px;
}
.wab-upload-close {
    width: 44px;
    height: 44px;
    margin-left: 8px;
    border: 0;
    border-radius: 50%;
    background: transparent;
    color: #667781;
    display: grid;
    place-items: center;
    cursor: pointer;
}
.wab-upload-close:hover { background: #e9edef; color: #111b21; }
.wab-upload-review-body {
    display: grid;
    place-items: center;
    padding: 28px;
    overflow: auto;
}
.wab-upload-preview {
    width: min(620px, 100%);
    display: grid;
    place-items: center;
}
.wab-upload-preview img,
.wab-upload-preview video {
    max-width: 100%;
    max-height: min(58vh, 520px);
    border-radius: 8px;
    object-fit: contain;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(11,20,26,.08);
}
.wab-upload-doc-preview {
    width: min(560px, 100%);
    min-height: 330px;
    border-radius: 8px;
    background: #e9edef;
    display: grid;
    place-items: center;
    padding: 34px;
    text-align: center;
}
.wab-upload-doc-preview svg { color: #667781; margin-bottom: 24px; }
.wab-upload-doc-preview strong {
    display: block;
    color: #3b4a54;
    font-size: 26px;
    font-weight: 500;
    margin-bottom: 8px;
}
.wab-upload-doc-preview span { color: #667781; font-size: 15px; }
.wab-upload-tray {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    overflow-x: auto;
    background: #f7f8fa;
    border-top: 1px solid #d1d7db;
}
.wab-upload-thumb-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}
.wab-upload-thumb {
    width: 58px;
    height: 58px;
    border: 1px solid #d1d7db;
    border-radius: 8px;
    background: #ffffff;
    color: #3b4a54;
    display: grid;
    place-items: center;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    flex: 0 0 auto;
    padding: 0;
    transition: transform .15s, border-color .15s;
}
.wab-upload-thumb-wrap.is-active .wab-upload-thumb {
    border: 3px solid #25d366;
    box-shadow: 0 0 0 2px rgba(37, 211, 102, 0.25);
}
.wab-upload-thumb-del {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ea4335;
    color: #ffffff;
    border: 2px solid #ffffff;
    display: grid;
    place-items: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.25);
    z-index: 10;
    transition: transform .12s, background-color .12s;
    padding: 0;
}
.wab-upload-thumb-del:hover {
    background: #c5221f;
    transform: scale(1.18);
}
.wab-upload-thumb-del svg {
    width: 10px;
    height: 10px;
    stroke-width: 3;
}
.wab-upload-thumb img,
.wab-upload-thumb video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.wab-upload-thumb span {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
}
.wab-upload-count {
    position: absolute;
    right: 18px;
    bottom: 18px;
    min-width: 28px;
    height: 28px;
    padding: 0 8px;
    border-radius: 999px;
    background: #25d366;
    color: #111b21;
    display: grid;
    place-items: center;
    font-weight: 800;
    font-size: 13px;
}
.wab-upload-review-footer {
    background: #ffffff;
    border-top: 1px solid #d1d7db;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 22px min(14vw, 180px);
}
.wab-upload-caption {
    flex: 1;
    height: 54px;
    border: 0;
    outline: 0;
    border-radius: 8px;
    background: #f0f2f5;
    color: #111b21;
    padding: 0 18px;
    font-size: 16px;
}
.wab-upload-caption::placeholder { color: #667781; }
.wab-upload-send {
    width: 72px;
    height: 72px;
    border: 0;
    border-radius: 50%;
    background: #00a884;
    color: #ffffff;
    display: grid;
    place-items: center;
    cursor: pointer;
    flex: 0 0 auto;
    transition: transform .15s, opacity .15s;
}
.wab-upload-send:hover { transform: scale(1.04); }
.wab-upload-send:disabled { opacity: .55; cursor: wait; transform: none; }

/* ════════════════════════════════════════
   MODAL
════════════════════════════════════════ */
.wab-modal-content {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(17,27,33,.2);
}
.wab-modal-header {
    background: linear-gradient(135deg, #128c7e, #25d366);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.wab-modal-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.wab-modal-icon svg { stroke: #fff; }
.wab-modal-title { color: #fff; font-size: 16px; font-weight: 700; font-family: var(--wa-font); margin: 0; flex: 1; }
.wab-modal-body  { padding: 20px; background: #fafafa; }
.wab-modal-footer {
    padding: 14px 20px;
    background: #fafafa;
    border-top: 1px solid var(--wa-border);
    display: flex; justify-content: flex-end; gap: 10px;
}

.wab-form-label { font-size: 12px; font-weight: 600; color: var(--wa-text-muted); letter-spacing: .4px; text-transform: uppercase; display: block; margin-bottom: 6px; }
.wab-modal-row  { display: flex; gap: 8px; }
.wab-form-select,
.wab-form-input {
    height: 42px;
    border: 1.5px solid var(--wa-border-dark);
    border-radius: 8px;
    padding: 0 12px;
    font-size: 14px;
    font-family: var(--wa-font);
    color: var(--wa-text-main);
    background: #fff;
    outline: 0;
    width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.wab-form-input[rows] { height: auto; padding-top: 10px; display: block; }
.wab-form-select:focus, .wab-form-input:focus {
    border-color: var(--wa-green);
    box-shadow: 0 0 0 3px rgba(37,211,102,.15);
}
.wab-form-note { font-size: 11.5px; color: var(--wa-text-sub); margin-top: 10px; line-height: 1.5; }

.wab-btn {
    height: 38px;
    padding: 0 20px;
    border: 0; border-radius: 20px;
    font-size: 13.5px; font-weight: 600; font-family: var(--wa-font);
    cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
    transition: background .15s, transform .12s, box-shadow .15s;
}
.wab-btn:active { transform: scale(.96); }
.wab-btn--ghost {
    background: var(--wa-bg-header);
    color: var(--wa-text-muted);
}
.wab-btn--ghost:hover { background: #e2e6ea; color: var(--wa-text-main); }
.wab-btn--primary {
    background: linear-gradient(135deg, #128c7e, #25d366);
    color: #fff;
    box-shadow: 0 3px 12px rgba(37,211,102,.35);
}
.wab-btn--primary:hover {
    box-shadow: 0 5px 18px rgba(37,211,102,.45);
    filter: brightness(1.06);
}

.wab-settings-trigger { border: 0; cursor: pointer; color: var(--wa-text-muted); }
.wab-profile-trigger { cursor: pointer; }
.wab-panel-tabs {
    display: flex;
    gap: 6px;
    padding: 7px 12px;
    border-bottom: 1px solid var(--wa-border);
    background: #fff;
    flex-shrink: 0;
    overflow-x: auto;
}
.wab-panel-chip {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--wa-bg-header);
    color: var(--wa-text-muted);
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    flex: 0 0 auto;
}
.wab-panel-chip:hover,
.wab-panel-chip.is-active { background: var(--wa-teal); color: #fff; }
.wab-panel-card {
    width: min(100%, 920px);
    align-self: center;
    background: rgba(255,255,255,.96);
    border: 1px solid var(--wa-border-dark);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(17,27,33,.14);
    margin-bottom: 12px;
}
.wab-panel-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 12px;
    background: var(--wa-bg-header);
}
.wab-panel-card-head strong { display: block; color: var(--wa-text-main); font-size: 13px; }
.wab-panel-card-head span,
.wab-panel-card-head a { color: var(--wa-text-muted); font-size: 11px; text-decoration: none; }
.wab-panel-table-wrap { max-height: 260px; overflow: auto; }
.wab-panel-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    color: var(--wa-text-main);
}
.wab-panel-table th,
.wab-panel-table td {
    padding: 9px 10px;
    border-bottom: 1px solid var(--wa-border);
    text-align: left;
    white-space: nowrap;
}
.wab-panel-table th {
    background: #fff;
    color: var(--wa-text-muted);
    font-weight: 700;
    position: sticky;
    top: 0;
}
.wab-settings-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}
.wab-setting-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #fff;
    border: 1px solid var(--wa-border-dark);
    border-radius: 10px;
    cursor: pointer;
}
.wab-toggle-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.wab-toggle-switch {
    width: 40px;
    height: 22px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    padding: 2px;
    background: #d1d7db;
    transition: background .16s ease;
    flex: 0 0 auto;
}
.wab-toggle-switch::after {
    content: '';
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(17,27,33,.25);
    transition: transform .16s ease;
}
.wab-toggle-option input:checked + .wab-toggle-switch {
    background: var(--wa-teal);
}
.wab-toggle-option input:checked + .wab-toggle-switch::after {
    transform: translateX(18px);
}
.wab-setting-letter {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(37,211,102,.14);
    color: var(--wa-teal);
    font-size: 12px;
    font-weight: 800;
}
.wab-setting-option strong { color: var(--wa-text-main); font-size: 13px; }
.wab-profile-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 12px 0;
    border-bottom: 1px solid var(--wa-border);
    font-size: 13px;
}
.wab-profile-line span { color: var(--wa-text-muted); }
.wab-profile-line strong { color: var(--wa-text-main); text-align: right; }
.wab-label-btn {
    height: 36px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border: 0;
    border-radius: 18px;
    padding: 0 12px;
    background: rgba(84,101,111,.12);
    color: var(--wa-text-main);
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.wab-chat-label-row {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 4px;
}
.wab-chat-label-row span,
.wab-contact-labels span {
    display: inline-flex;
    align-items: center;
    max-width: 140px;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 700;
}
.wab-label-create-form {
    padding: 0 20px 18px;
    background: #fafafa;
}
.wab-label-create-title {
    color: var(--wa-text-muted);
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 8px;
    text-transform: uppercase;
}
.wab-label-create-row {
    display: flex;
    align-items: center;
    gap: 8px;
}
.wab-existing-labels {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}
.wab-existing-labels span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid;
    border-radius: 999px;
    padding: 5px 9px;
    font-size: 12px;
    font-weight: 800;
}
.wab-existing-labels i {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
}
.wab-existing-labels em {
    color: var(--wa-text-sub);
    font-size: 12px;
    font-style: normal;
}
.wab-color-input {
    width: 44px;
    height: 42px;
    border: 1px solid var(--wa-border-dark);
    border-radius: 8px;
    background: #fff;
    padding: 4px;
}
.wab-label-list {
    display: grid;
    gap: 8px;
}
.wab-label-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border: 1px solid var(--wa-border-dark);
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
}
.wab-label-option span {
    width: 16px;
    height: 16px;
    border-radius: 50%;
}
.wab-label-option strong {
    color: var(--wa-text-main);
    font-size: 13px;
}
.wab-empty-labels {
    color: var(--wa-text-muted);
    font-size: 13px;
    text-align: center;
    padding: 20px;
}
.wab-contact-info-body {
    text-align: center;
}
.wab-profile-photo {
    width: 132px;
    height: 132px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 800;
    margin: 10px auto 18px;
}
.wab-contact-title {
    color: var(--wa-text-main);
    font-size: 22px;
    font-weight: 800;
}
.wab-contact-sub {
    color: var(--wa-text-muted);
    font-size: 14px;
    margin-top: 4px;
}
.wab-contact-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #00a884;
    font-size: 13px;
    font-weight: 700;
    margin: 12px 0;
}
.wab-contact-status span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #25d366;
}
.wab-contact-label-action {
    height: 38px;
    border: 0;
    border-radius: 20px;
    padding: 0 18px;
    background: rgba(84,101,111,.12);
    color: var(--wa-text-main);
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    margin-bottom: 12px;
}
.wab-contact-labels {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
    min-height: 24px;
    margin-bottom: 14px;
}
.wab-contact-labels em {
    color: var(--wa-text-sub);
    font-size: 12px;
    font-style: normal;
}
.wab-notes-box {
    margin-top: 14px;
    padding: 14px;
    text-align: left;
    border: 1px dashed var(--wa-border-dark);
    border-radius: 10px;
    color: var(--wa-text-sub);
    background: #fff;
    font-size: 13px;
}

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 768px) {
    .wab-page {
        grid-template-columns: 1fr !important;
        height: calc(100vh - 72px);
        margin-top: 0;
        border-radius: 0;
    }
    .wab-sidebar {
        position: absolute;
        inset: 0;
        z-index: 10;
        width: 100%;
        transform: translateX(0);
        transition: transform .22s ease;
    }
    .wab-sidebar.mobile-hidden {
        transform: translateX(-100%);
        pointer-events: none;
    }
    .wab-conversation { position: absolute; inset: 0; z-index: 5; }
    .wab-mobile-back  { display: inline-flex; }
    .wab-msg-row      { max-width: 85%; }
}

/* ════════════════════════════════════════
   LABEL DOTS IN CONTACT LIST
════════════════════════════════════════ */
.wab-contact-row-right {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}
.wab-contact-label-dots {
    display: flex;
    align-items: center;
    gap: 3px;
}
.wab-label-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 1.5px rgba(0,0,0,0.08);
}
.wab-contact-label-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 3px;
    margin-top: 3px;
}
.wab-contact-tag-chip {
    display: inline-flex;
    align-items: center;
    padding: 1px 6px;
    border-radius: 999px;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: .2px;
    max-width: 90px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ════════════════════════════════════════
   PROFILE RIGHT PANEL
════════════════════════════════════════ */
.wab-profile-panel {
    position: fixed;
    top: 0;
    right: -360px;
    width: 360px;
    height: 100vh;
    background: #fff;
    box-shadow: -4px 0 28px rgba(17,27,33,.18);
    z-index: 9999;
    transition: right .28s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.wab-profile-panel.is-open { right: 0; }
.wab-profile-panel-inner {
    flex: 1;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #d1d7db transparent;
}
.wab-profile-panel-inner::-webkit-scrollbar { width: 4px; }
.wab-profile-panel-inner::-webkit-scrollbar-thumb { background: #d1d7db; border-radius: 10px; }
.wab-pp-overlay {
    position: fixed;
    inset: 0;
    background: rgba(17,27,33,.25);
    z-index: 9998;
    display: none;
    animation: overlay-in .2s ease;
}
.wab-pp-overlay.is-visible { display: block; }
@keyframes overlay-in { from { opacity:0; } to { opacity:1; } }

/* Panel Header */
.wab-pp-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 16px;
    height: 60px;
    background: linear-gradient(135deg, #128c7e, #25d366);
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    flex-shrink: 0;
}
.wab-pp-header .wab-icon-btn { color: #fff; }
.wab-pp-header .wab-icon-btn:hover { background: rgba(255,255,255,.2); color: #fff; }

/* Hero section */
.wab-pp-hero {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 28px 20px 20px;
    background: linear-gradient(180deg, #f0f2f5 0%, #fff 100%);
    text-align: center;
    border-bottom: 1px solid var(--wa-border);
}
.wab-pp-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 46px;
    font-weight: 800;
    margin-bottom: 14px;
    box-shadow: 0 4px 20px rgba(17,27,33,.14);
}
.wab-pp-name {
    font-size: 20px;
    font-weight: 800;
    color: var(--wa-text-main);
    margin-bottom: 4px;
}
.wab-pp-phone {
    font-size: 14px;
    color: var(--wa-text-muted);
    margin-bottom: 8px;
}
.wab-pp-biz {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #00a884;
}

/* Actions */
.wab-pp-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    padding: 14px 20px;
    border-bottom: 1px solid var(--wa-border);
}
.wab-pp-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 16px;
    border: 0;
    border-radius: 20px;
    background: rgba(37,211,102,.12);
    color: var(--wa-green-dark);
    font-size: 13px;
    font-weight: 700;
    font-family: var(--wa-font);
    cursor: pointer;
    transition: background .15s, transform .12s;
}
.wab-pp-action-btn:hover { background: rgba(37,211,102,.22); transform: scale(1.04); }
.wab-pp-action-btn svg { stroke: var(--wa-green-dark); }

/* Sections */
.wab-pp-section {
    padding: 16px 20px;
    border-bottom: 1px solid var(--wa-border);
}
.wab-pp-section-title {
    font-size: 11px;
    font-weight: 800;
    color: var(--wa-text-muted);
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 12px;
}
.wab-pp-label-list {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
}
.wab-pp-label-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}
.wab-pp-label-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}
.wab-pp-no-labels {
    font-size: 12px;
    color: var(--wa-text-sub);
    font-style: italic;
}
.wab-pp-stat-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 0;
    border-bottom: 1px solid var(--wa-border);
    font-size: 13px;
}
.wab-pp-stat-row:last-child { border-bottom: none; }
.wab-pp-stat-row span { color: var(--wa-text-muted); }
.wab-pp-stat-row strong { color: var(--wa-text-main); }
.wab-pp-notes-box {
    padding: 14px;
    border: 1.5px dashed var(--wa-border-dark);
    border-radius: 10px;
    color: var(--wa-text-sub);
    background: #fafafa;
    font-size: 13px;
    cursor: text;
    transition: border-color .15s;
}
.wab-pp-notes-box:hover { border-color: var(--wa-green); }

/* ════════════════════════════════════════
   TABLE IMPROVEMENTS
════════════════════════════════════════ */
.wab-panel-card-badge {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: var(--wa-teal);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.wab-panel-close-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 6px;
    background: rgba(84,101,111,.1);
    color: var(--wa-text-muted);
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: background .15s, color .15s;
}
.wab-panel-close-btn:hover { background: rgba(239,83,80,.12); color: #ef5350; }
.wab-panel-close-btn svg { stroke: currentColor; }
.wab-table-num {
    color: var(--wa-text-sub);
    font-size: 11px;
    font-weight: 600;
    text-align: center;
}
.wab-status-pill {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 700;
    white-space: nowrap;
}
.wab-status-pill--success { background: rgba(37,211,102,.12); color: #128c7e; }
.wab-status-pill--danger  { background: rgba(239,83,80,.12);  color: #c62828; }
.wab-status-pill--warning { background: rgba(255,167,38,.15); color: #e65100; }
.wab-status-pill--neutral { background: rgba(84,101,111,.1);  color: #54656f; }
.wab-panel-table tr:hover td { background: #f5f6f6; }

/* ════════════════════════════════════════
   iOS-STYLE TOGGLE SWITCH (Panel Settings)
════════════════════════════════════════ */
.wab-section-label {
    font-size: 11px;
    font-weight: 800;
    color: var(--wa-text-muted);
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 10px;
    padding: 0 4px;
}
.wab-toggle-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.wab-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: #fff;
    border: 1px solid var(--wa-border);
    border-radius: 12px;
    cursor: pointer;
    transition: background .12s;
    gap: 10px;
    margin: 0;
}
.wab-toggle-row:hover { background: #f8f9fa; }
.wab-toggle-row-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.wab-panel-short-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #128c7e, #25d366);
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.wab-toggle-row-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--wa-text-main);
}
/* iOS Toggle */
.wab-ios-toggle {
    position: relative;
    width: 46px;
    height: 26px;
    flex-shrink: 0;
}
.wab-ios-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.wab-ios-slider {
    position: absolute;
    inset: 0;
    border-radius: 26px;
    background: #ccc;
    transition: background .2s;
    cursor: pointer;
}
.wab-ios-slider::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #fff;
    top: 3px;
    left: 3px;
    transition: transform .2s;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
}
.wab-ios-toggle input:checked + .wab-ios-slider { background: #25d366; }
.wab-ios-toggle input:checked + .wab-ios-slider::before { transform: translateX(20px); }

/* ════════════════════════════════════════
   LABEL CREATE SECTION (In Settings Modal)
════════════════════════════════════════ */
.wab-label-create-section {
    padding: 16px 0 18px;
    border-top: 1px solid var(--wa-border);
    background: #fafafa;
    border-radius: 0 0 16px 16px;
}
.wab-label-create-row-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px 8px;
}
.wab-label-color-preview {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    flex-shrink: 0;
    border: 2px solid rgba(0,0,0,.1);
    transition: background .15s;
}
.wab-existing-labels-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 6px 20px 4px;
    min-height: 30px;
}
.wab-exist-label-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid var(--wa-border-dark);
    font-size: 12px;
    font-weight: 600;
    color: var(--wa-text-main);
    cursor: default;
    transition: box-shadow .12s;
}
.wab-exist-label-pill:hover { box-shadow: 0 2px 8px rgba(17,27,33,.12); }
.wab-exist-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.wab-no-labels-hint {
    font-size: 12px;
    color: var(--wa-text-sub);
    font-style: italic;
    padding: 4px 0;
}

/* ════════════════════════════════════════
   WHATSAPP-STYLE LABEL ASSIGN MODAL
════════════════════════════════════════ */
.wab-label-search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--wa-bg-header);
    border-radius: 10px;
    padding: 9px 12px;
    margin-bottom: 12px;
}
.wab-label-search-box svg { flex-shrink: 0; color: var(--wa-text-sub); }
.wab-label-search-box input {
    border: 0;
    background: transparent;
    outline: 0;
    font-size: 13px;
    color: var(--wa-text-main);
    font-family: var(--wa-font);
    width: 100%;
}
.wab-label-search-box input::placeholder { color: var(--wa-text-sub); }

.wab-wa-label-list {
    display: flex;
    flex-direction: column;
    gap: 2px;
    max-height: 320px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #d1d7db transparent;
}
.wab-wa-label-list::-webkit-scrollbar { width: 4px; }
.wab-wa-label-list::-webkit-scrollbar-thumb { background: #d1d7db; border-radius: 10px; }

.wab-wa-label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    border-radius: 12px;
    cursor: pointer;
    transition: background .12s, transform .1s;
    border: 1.5px solid transparent;
    gap: 10px;
    user-select: none;
}
.wab-wa-label-row:hover { background: #f0f2f5; }
.wab-wa-label-row.is-selected {
    background: rgba(37,211,102,.07);
    border-color: rgba(37,211,102,.25);
}
.wab-wa-label-row:active { transform: scale(.98); }
.wab-wa-label-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.wab-wa-label-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 15px;
    font-weight: 800;
    flex-shrink: 0;
}
.wab-wa-label-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--wa-text-main);
}
.wab-wa-check {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background .15s, border-color .15s, transform .15s;
    background: transparent;
}
.wab-wa-label-row.is-selected .wab-wa-check {
    transform: scale(1.1);
}
.wab-wa-label-row:not(.is-selected) .wab-wa-check svg { display: none; }

/* ════════════════════════════════════════
   IMPORT CONTACTS MODAL STYLES
════════════════════════════════════════ */
.wa-import-info-bar {
    display: flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(135deg,rgba(37,211,102,.07),rgba(18,140,126,.07));
    border: 1px solid rgba(37,211,102,.25);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12.5px;
    color: var(--wa-text-main);
    margin-bottom: 16px;
    flex-wrap: wrap;
    line-height: 1.5;
}
.wa-import-info-bar code {
    background: rgba(37,211,102,.13);
    color: #128c7e;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 11.5px;
    font-weight: 600;
}
.wa-import-sample-link {
    margin-left: auto;
    color: var(--wa-teal);
    font-weight: 600;
    font-size: 12px;
    text-decoration: none;
    white-space: nowrap;
}
.wa-import-sample-link:hover { text-decoration: underline; }

.wa-import-drop {
    border: 2.5px dashed rgba(37,211,102,.45);
    border-radius: 14px;
    padding: 36px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color .18s, background .18s;
    background: rgba(37,211,102,.03);
    position: relative;
}
.wa-import-drop:hover,
.wa-import-drop.drag-over {
    border-color: var(--wa-green);
    background: rgba(37,211,102,.08);
}
.wa-import-drop-icon { margin-bottom: 10px; display: flex; justify-content: center; }
.wa-import-drop-title { font-size: 15px; font-weight: 700; color: var(--wa-text-main); margin-bottom: 5px; }
.wa-import-drop-sub   { font-size: 13px; color: var(--wa-text-muted); }
.wa-import-browse-link {
    color: var(--wa-teal);
    font-weight: 700;
    cursor: pointer;
    text-decoration: underline;
}
.wa-import-drop-hint  { font-size: 11.5px; color: var(--wa-text-sub); margin-top: 8px; }

.wa-import-file-info {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(37,211,102,.07);
    border: 1px solid rgba(37,211,102,.3);
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 12px;
    font-size: 13px;
    font-weight: 600;
    color: var(--wa-text-main);
}
.wa-import-file-size { font-size: 11.5px; color: var(--wa-text-sub); font-weight: 400; }
.wa-import-file-remove {
    margin-left: auto;
    width: 24px; height: 24px;
    border: 0; border-radius: 50%;
    background: rgba(239,83,80,.1);
    color: #ef5350;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .14s;
}
.wa-import-file-remove:hover { background: rgba(239,83,80,.22); }

.wa-import-error {
    background: rgba(239,83,80,.08);
    border: 1px solid rgba(239,83,80,.3);
    border-radius: 10px;
    color: #c62828;
    font-size: 13px;
    padding: 10px 14px;
    margin-top: 12px;
    font-weight: 500;
}

/* Preview Table */
.wa-import-preview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    gap: 10px;
}
.wa-import-preview-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--wa-text-main);
}
.wa-import-back-btn {
    border: 1px solid var(--wa-border-dark);
    background: transparent;
    color: var(--wa-text-muted);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex; align-items: center; gap: 4px;
    transition: background .12s;
}
.wa-import-back-btn:hover { background: var(--wa-hover); }
.wa-import-table-wrap {
    max-height: 260px;
    overflow-y: auto;
    border-radius: 10px;
    border: 1px solid var(--wa-border-dark);
    scrollbar-width: thin;
    scrollbar-color: #d1d7db transparent;
}
.wa-import-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.wa-import-table thead {
    position: sticky;
    top: 0;
    background: var(--wa-bg-header);
    z-index: 1;
}
.wa-import-table th {
    padding: 9px 12px;
    font-weight: 700;
    color: var(--wa-text-muted);
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: .4px;
    border-bottom: 1px solid var(--wa-border-dark);
    text-align: left;
}
.wa-import-table td {
    padding: 8px 12px;
    border-bottom: 1px solid var(--wa-border);
    color: var(--wa-text-main);
    vertical-align: middle;
}
.wa-import-table tr:last-child td { border-bottom: 0; }
.wa-import-table tr:nth-child(even) td { background: rgba(0,0,0,.02); }
.wa-import-row-ok   { color: #25d366; font-size: 11.5px; font-weight: 700; }
.wa-import-row-warn { color: #ffa726; font-size: 11.5px; font-weight: 700; }
.wa-import-skipped {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 10px;
    font-size: 12.5px;
    color: #e65100;
    background: rgba(255,167,38,.08);
    border: 1px solid rgba(255,167,38,.3);
    border-radius: 8px;
    padding: 8px 12px;
}

/* Progress */
.wa-import-spinner {
    width: 44px; height: 44px;
    border: 4px solid rgba(37,211,102,.2);
    border-top-color: var(--wa-green);
    border-radius: 50%;
    animation: wa-spin 0.8s linear infinite;
    margin: 0 auto 16px;
}
@keyframes wa-spin { to { transform: rotate(360deg); } }
.wa-import-progress-text { font-size: 14px; font-weight: 600; color: var(--wa-text-main); margin-bottom: 14px; }
.wa-import-progress-bar-wrap {
    height: 8px;
    background: rgba(37,211,102,.15);
    border-radius: 10px;
    overflow: hidden;
    margin: 0 auto;
    max-width: 320px;
}
.wa-import-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #25d366, #128c7e);
    border-radius: 10px;
    transition: width .3s ease;
}

/* Done state */
.wa-import-done-icon { margin-bottom: 14px; animation: wa-done-pop .4s cubic-bezier(.34,1.56,.64,1); }
@keyframes wa-done-pop { 0% { transform: scale(0.4); opacity:0; } 100% { transform: scale(1); opacity:1; } }
.wa-import-done-title { font-size: 18px; font-weight: 800; color: var(--wa-text-main); margin-bottom: 6px; }
.wa-import-done-sub   { font-size: 13px; color: var(--wa-text-muted); }
</style>

{{-- ══════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════ --}}
@if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
    if ((!window.Echo || typeof window.Echo.private !== 'function') && window.Pusher && typeof Echo !== 'undefined') {
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: @json(config('broadcasting.connections.pusher.key')),
            cluster: @json(config('broadcasting.connections.pusher.options.cluster')),
            forceTLS: true,
            encrypted: true,
            authEndpoint: '{{ url('/broadcasting/auth') }}',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            },
        });
    }
</script>
@endif

<script>
(function () {
    const page          = document.getElementById('wabPage');
    const sidebar       = document.getElementById('wabSidebar');
    const toggleBtn     = document.getElementById('wabToggleSidebar');
    const emojiBtn      = document.getElementById('wabEmojiBtn');
    const emojiPanel    = document.getElementById('wabEmojiPanel');
    const attachBtn     = document.getElementById('wabAttachBtn');
    const attachMenu    = document.getElementById('wabAttachMenu');
    const documentInput = document.getElementById('wabDocumentInput');
    const photoVideoInput = document.getElementById('wabPhotoVideoInput');
    const sendBtn       = document.getElementById('wabSendBtn');
    const input         = document.getElementById('wabInput');
    const sendForm      = document.querySelector('.wab-conv-footer');
    const body          = document.getElementById('wabMessagesBody');
    const uploadReview  = document.getElementById('wabUploadReview');
    const uploadClose   = document.getElementById('wabUploadClose');
    const uploadFileName = document.getElementById('wabUploadFileName');
    const uploadPreview = document.getElementById('wabUploadPreview');
    const uploadTray    = document.getElementById('wabUploadTray');
    const uploadCaption = document.getElementById('wabUploadCaption');
    const uploadSend    = document.getElementById('wabUploadSend');
    const micBtn        = document.getElementById('wabMicBtn');
    const audioReview   = document.getElementById('wabAudioReview');
    const audioDelete   = document.getElementById('wabAudioDelete');
    const audioPlay     = document.getElementById('wabAudioPlay');
    const audioTime     = document.getElementById('wabAudioTime');
    const audioWave     = document.getElementById('wabAudioWave');
    const searchInput   = document.getElementById('wabSearchInput');
    const mobileBack    = document.getElementById('wabMobileBack');
    const typingRow     = document.getElementById('wabTypingRow');
    const typingLabel   = document.getElementById('wabTypingLabel');
    const chatMoreBtn   = document.getElementById('wabChatMoreBtn');
    const chatMenu      = document.getElementById('wabChatMenu');
    const markUnreadBtn = document.getElementById('wabMarkUnreadBtn');
    const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let selectedPhone   = body?.dataset.selectedPhone || '';
    let selectedPhoneChannel = selectedPhone.replace(/\D+/g, '');
    const messagesUrl   = @json(route('whatsapp.chat.messages'));
    const contactListUrl = @json(route('whatsapp.chat.contacts'));
    const markReadUrl   = @json(route('whatsapp.chat.mark-read'));
    const markUnreadUrl = @json(route('whatsapp.chat.mark-unread'));
    const mediaUploadUrl = @json(route('whatsapp.chat.send-media'));
    let lastMessageId   = Number(body?.dataset.lastMessageId || 0);
    let firstMessageId  = Number(body?.dataset.firstMessageId || 0);
    let hasMoreOlder    = body?.dataset.hasMoreOlder === '1';
    let isLoadingOlder  = false;
    const olderLoader   = document.getElementById('wabOlderMessagesLoader');
    let isPolling       = false;
    let selectedUploadFiles = [];
    let selectedUploadUrls = [];
    let activeUploadIndex = 0;
    let mediaRecorder = null;
    let audioChunks = [];
    let audioStream = null;
    let audioStartedAt = 0;
    let audioTimer = null;
    let recordedAudioBlob = null;
    let recordedAudioUrl = '';
    let recordedAudio = null;
    let recordedAudioDuration = 0;
    let recordedAudioExtension = 'webm';
    let defaultTypingLabel = typingLabel?.textContent || selectedPhone || 'ready';

    /* ── Sidebar pagination & search state ── */
    let contactPage = 1;
    let contactsHasMore = @json((bool)($contactsHasMore ?? true));
    let isLoadingContacts = false;
    let contactSearchQuery = '';
    let searchDebounceTimer = null;
    const chatPreloader = document.getElementById('wabChatPreloader');
    const contactListLoader = document.getElementById('wabContactListLoader');



    /* ── Mobile back ── */
    mobileBack?.addEventListener('click', () => {
        sidebar.classList.remove('mobile-hidden');
    });

    /* ── Contact click ── */
    chatMoreBtn?.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        chatMenu?.classList.toggle('is-open');
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('.wab-chat-more')) {
            chatMenu?.classList.remove('is-open');
        }
    });

    markUnreadBtn?.addEventListener('click', async () => {
        if (!selectedPhone) return;
        try {
            const response = await fetch(markUnreadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ phone: selectedPhone }),
            });
            if (response.ok) {
                const data = await response.json();
                updateContacts(data.contacts);
                window.location.href = @json(route('whatsapp.chat'));
            }
        } catch (error) {
            console.warn('Unable to mark WhatsApp chat unread.', error);
        }
    });

    /* ── In-Memory Cache (SWR - 0ms instant contact switching) ── */
    const chatCache = new Map();

    // Cache initial messages if present on page load
    if (selectedPhone && body && body.querySelectorAll('.wab-msg-row:not(.wab-empty-message)').length > 0) {
        chatCache.set(selectedPhone, {
            lastMessageId: lastMessageId,
            firstMessageId: firstMessageId,
            hasMoreOlder: hasMoreOlder,
            bodyHtml: body.innerHTML,
        });
    }

    /* ── Dynamic Live Chat Switch (Always fetch fresh latest data) ── */
    async function switchChat(phone, name, color) {
        if (!phone) return;

        selectedPhone = phone;
        selectedPhoneChannel = phone.replace(/\D+/g, '');
        defaultTypingLabel = phone;
        window.selectedCustomerPhone = phone;
        if (typeof window.resetLeadsOrdersPhone === 'function') {
            window.resetLeadsOrdersPhone(phone);
        }

        // Show header action buttons container immediately
        const actionBtns = document.getElementById('wabChatActionButtons');
        if (actionBtns) actionBtns.classList.remove('d-none');
        const chatMore = document.getElementById('wabChatMoreWrapper');
        if (chatMore) chatMore.classList.remove('d-none');

        // Update active class in sidebar
        document.querySelectorAll('.wab-contact-item').forEach(i => {
            const isActive = i.dataset.phone === phone;
            i.classList.toggle('is-active', isActive);
            if (isActive) {
                i.querySelector('.wab-badge')?.remove();
            }
        });

        // Hide sidebar on mobile
        if (window.innerWidth <= 768 && sidebar) sidebar.classList.add('mobile-hidden');

        // Update browser URL without reloading
        const newUrl = contactUrl(phone);
        history.pushState({ phone, name, color }, '', newUrl);

        // Show conversation containers
        document.querySelector('.wab-blank-chat')?.classList.add('d-none');
        document.querySelector('.wab-conv-header')?.classList.remove('d-none');
        document.querySelector('.wab-conv-footer')?.classList.remove('d-none');
        if (body) {
            body.classList.remove('d-none');
            body.dataset.selectedPhone = phone;
        }

        // Enable message form
        if (input) {
            input.disabled = false;
            input.placeholder = 'Type a message…';
            input.focus();
        }
        if (sendBtn) sendBtn.disabled = false;
        if (micBtn) micBtn.disabled = false;

        const hiddenPhoneInput = document.querySelector('.wab-conv-footer input[name="phone"]');
        if (hiddenPhoneInput) hiddenPhoneInput.value = phone;

        // Update header basics immediately
        updateHeaderBasic(name, phone, color);

        // Show Preloader
        if (chatPreloader) chatPreloader.classList.remove('d-none');
        clearMessagesBody();

        try {
            const res = await fetch(`${messagesUrl}?phone=${encodeURIComponent(phone)}&limit=30&with_summary=1`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error('Failed to load chat');

            const data = await res.json();
            const msgs = data.messages || [];

            // If user did not switch away while request was in-flight
            if (selectedPhone === phone) {
                lastMessageId = Number(data.last_id || (msgs[msgs.length - 1]?.id || 0));
                firstMessageId = Number(data.first_id || (msgs[0]?.id || 0));
                hasMoreOlder = Boolean(data.has_more_older);

                if (body) {
                    body.dataset.lastMessageId = String(lastMessageId);
                    body.dataset.firstMessageId = String(firstMessageId);
                    body.dataset.hasMoreOlder = hasMoreOlder ? '1' : '0';
                }
                if (olderLoader) olderLoader.classList.toggle('d-none', !hasMoreOlder);

                // Render fresh messages
                renderInitialMessagesBatch(msgs);

                if (data.customer) {
                    updateHeaderCustomerDetails(data.customer, name, phone, color);
                }
            }
        } catch (err) {
            console.warn('Error loading chat messages:', err);
        } finally {
            if (chatPreloader) chatPreloader.classList.add('d-none');
            if (body && selectedPhone === phone) body.scrollTop = body.scrollHeight;
        }
    }

    function updateHeaderBasic(name, phone, color) {
        const avatar = document.getElementById('wabOpenProfilePanel');
        if (avatar) {
            avatar.textContent = initials(name);
            avatar.style.background = `${color}1a`;
            avatar.style.color = color;
        }
        const nameEl = document.querySelector('.wab-conv-name');
        if (nameEl) nameEl.textContent = name || phone;
        if (typingLabel) typingLabel.textContent = phone;
    }

    function updateHeaderCustomerDetails(customer, fallbackName, phone, color) {
        const nameEl = document.querySelector('.wab-conv-name');
        const resolvedName = customer.name || fallbackName || phone;
        if (nameEl) nameEl.textContent = resolvedName;

        const avatar = document.getElementById('wabOpenProfilePanel');
        if (avatar) {
            avatar.textContent = initials(resolvedName);
            avatar.style.background = `${color}1a`;
            avatar.style.color = color;
        }

        // Ensure action buttons are visible
        const actionBtns = document.getElementById('wabChatActionButtons');
        if (actionBtns) actionBtns.classList.remove('d-none');
        const chatMore = document.getElementById('wabChatMoreWrapper');
        if (chatMore) chatMore.classList.remove('d-none');

        // Labels row in header
        let labelRow = document.querySelector('.wab-chat-label-row');
        const userInfo = document.querySelector('.wab-conv-user-info');
        const labels = Array.isArray(customer.labels) ? customer.labels : [];
        if (labels.length > 0) {
            const labelsHtml = labels.map(l => `<span style="background:${l.color}1a;color:${l.color};border:1px solid ${l.color}30">${escapeHtml(l.name)}</span>`).join('');
            if (labelRow) {
                labelRow.innerHTML = labelsHtml;
            } else if (userInfo) {
                const newRow = document.createElement('div');
                newRow.className = 'wab-chat-label-row';
                newRow.innerHTML = labelsHtml;
                userInfo.appendChild(newRow);
            }
        } else if (labelRow) {
            labelRow.remove();
        }

        // Update Lead Button inside #waHeaderLeadBtnWrap
        const leadWrap = document.getElementById('waHeaderLeadBtnWrap');
        if (leadWrap) {
            if (customer.lead) {
                const leadId = customer.lead.order_id || customer.lead.id;
                const leadUrl = customer.lead.edit_url;
                leadWrap.innerHTML = `
                    <a href="${leadUrl}" target="_blank" class="wab-label-btn" style="background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9" title="View CRM Lead #${leadId}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7.5" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                        Lead #${leadId}
                    </a>
                `;
            } else {
                leadWrap.innerHTML = `
                    <button type="button" class="wab-label-btn" style="background:#00a884;color:#fff;border:none" data-bs-toggle="modal" data-bs-target="#waCreateLeadModal" title="Create Lead with this WhatsApp Customer">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        Create Lead
                    </button>
                `;
            }
        }

        // Update modal info & pre-fills
        const assignPhoneInput = document.getElementById('waAssignPhoneInput');
        if (assignPhoneInput) assignPhoneInput.value = phone;
        const assignSubtitle = document.getElementById('waAssignContactSubtitle');
        if (assignSubtitle) assignSubtitle.textContent = `for ${resolvedName} (${phone})`;

        // Check active label checkboxes in Label modal
        const labelIdSet = new Set(labels.map(l => parseInt(l.id)));
        document.querySelectorAll('.wa-contact-label-modal-chk').forEach(chk => {
            chk.checked = labelIdSet.has(parseInt(chk.dataset.labelId || chk.value));
        });

        // Update Check Leads / Orders modal subtitles
        const leadsModalSub = document.querySelector('#waCheckLeadsModal .opacity-75');
        if (leadsModalSub) leadsModalSub.innerHTML = `Customer: <strong>${escapeHtml(resolvedName)}</strong> (${phone})`;
        const ordersModalSub = document.querySelector('#waCheckOrdersModal .opacity-75');
        if (ordersModalSub) ordersModalSub.innerHTML = `Customer: <strong>${escapeHtml(resolvedName)}</strong> (${phone})`;

        // Update Create Lead modal inputs
        const createLeadModal = document.getElementById('waCreateLeadModal');
        if (createLeadModal) {
            const returnPhone = createLeadModal.querySelector('input[name="return_phone"]');
            if (returnPhone) returnPhone.value = phone;
            const mobileInput = createLeadModal.querySelector('input[name="mobile"]');
            if (mobileInput) mobileInput.value = phone.replace(/\D+/g, '').slice(-10);
            const nameInput = createLeadModal.querySelector('input[name="user_name"]');
            if (nameInput) nameInput.value = customer.name || fallbackName || '';
            const emailInput = createLeadModal.querySelector('input[name="email"]');
            if (emailInput && customer.user?.email) emailInput.value = customer.user.email;
        }
    }

    function getTypingRow() {
        let el = document.getElementById('wabTypingRow');
        if (!el && body) {
            el = document.createElement('div');
            el.id = 'wabTypingRow';
            el.className = 'wab-msg-row wab-incoming wab-typing-row';
            el.hidden = true;
            el.innerHTML = `
                <div class="wab-msg-bubble">
                    <div class="wab-typing-dots">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;
            body.appendChild(el);
        }
        return el;
    }

    function insertBeforeTyping(node) {
        if (!body || !node) return;
        const target = getTypingRow();
        if (target && target.parentNode === body) {
            body.insertBefore(node, target);
        } else {
            body.appendChild(node);
        }
    }

    function clearMessagesBody() {
        if (!body) return;
        body.querySelectorAll('.wab-msg-row:not(.wab-typing-row), .wab-date-badge, .wab-empty-message, .wab-panel-card').forEach(el => el.remove());
    }

    function renderInitialMessagesBatch(messages) {
        if (!body) return;

        if (!Array.isArray(messages) || messages.length === 0) {
            const emptyRow = document.createElement('div');
            emptyRow.className = 'wab-msg-row wab-incoming wab-empty-message';
            emptyRow.innerHTML = `
                <div class="wab-msg-bubble">
                    No messages yet. Start a conversation below.
                    <div class="wab-msg-meta"><span class="wab-msg-time"></span></div>
                </div>
            `;
            insertBeforeTyping(emptyRow);
            return;
        }

        let lastRenderDate = null;
        messages.forEach(msg => {
            const key = localDateKey(msg.created_at);
            if (key && key !== lastRenderDate) {
                const badge = document.createElement('div');
                badge.className = 'wab-date-badge';
                badge.dataset.dateKey = key;
                badge.textContent = dateLabel(msg.created_at);
                insertBeforeTyping(badge);
                lastRenderDate = key;
            }

            const hasMedia = Boolean(msg.media_url);
            const mediaClass = mediaTypeClass(msg.media_type);
            const row = document.createElement('div');
            row.className = `wab-msg-row ${msg.direction === 'inbound' ? 'wab-incoming' : 'wab-outgoing'}`;
            row.dataset.messageId = msg.id;
            row.innerHTML = `
                <div class="wab-msg-bubble ${hasMedia ? `wab-bubble--media ${mediaClass ? `wab-bubble--${mediaClass}` : ''}` : ''}">
                    ${messageContentMarkup(msg)}
                    <div class="wab-msg-meta">
                        <span class="wab-msg-time">${escapeHtml(msg.time || '')}</span>
                        ${msg.direction === 'outbound' ? tickMarkup(msg.status) : ''}
                    </div>
                </div>
            `;
            insertBeforeTyping(row);
            initVoiceCards(row);
        });
    }

    function bindContactClick(item) {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const phone = item.dataset.phone;
            const name = item.querySelector('.wab-contact-name')?.textContent || item.dataset.name || phone;
            const color = item.dataset.color || '#25d366';
            switchChat(phone, name, color);
        });
    }

    document.querySelectorAll('.wab-contact-item').forEach(bindContactClick);

    /* ── Sidebar Infinite Scroll ── */
    async function fetchNextContacts() {
        if (!contactsHasMore || isLoadingContacts) return;
        isLoadingContacts = true;
        if (contactListLoader) contactListLoader.classList.remove('d-none');

        try {
            const nextPage = contactPage + 1;
            const url = `${contactListUrl}?page=${nextPage}&search=${encodeURIComponent(contactSearchQuery)}&active_phone=${encodeURIComponent(selectedPhone)}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;

            const data = await res.json();
            const list = document.getElementById('wabContactList');
            const contacts = data.contacts || [];

            contacts.forEach(c => {
                const el = createContactItemElement(c);
                if (el && list) list.appendChild(el);
            });

            contactPage = nextPage;
            contactsHasMore = Boolean(data.has_more);
        } catch (err) {
            console.warn('Failed to load more contacts', err);
        } finally {
            isLoadingContacts = false;
            if (contactListLoader) contactListLoader.classList.add('d-none');
        }
    }

    const contactListEl = document.getElementById('wabContactList');
    if (contactListEl) {
        contactListEl.addEventListener('scroll', () => {
            if (contactListEl.scrollTop + contactListEl.clientHeight >= contactListEl.scrollHeight - 60) {
                if (contactsHasMore && !isLoadingContacts) {
                    fetchNextContacts();
                }
            }
        });
    }

    function createContactItemElement(c) {
        if (!c?.phone) return null;
        const cleanPhone = String(c.phone).replace(/\D+/g, '');
        const existing = document.querySelector(`.wab-contact-item[data-phone="${c.phone}"]`);
        if (existing) return null;

        const div = document.createElement('div');
        div.className = `wab-contact-item ${c.active || c.phone === selectedPhone ? 'is-active' : ''}`;
        div.id = `wab-contact-card-${cleanPhone}`;
        div.dataset.name = (c.name || '').toLowerCase();
        div.dataset.contactId = c.id || '';
        div.dataset.url = contactUrl(c.phone);
        div.dataset.phone = c.phone || '';
        div.dataset.color = c.color || '#25d366';
        div.dataset.badge = String(c.badge || 0);
        div.dataset.isGroup = c.is_group ? '1' : '0';

        const labels = Array.isArray(c.labels) ? c.labels : [];
        const labelIds = Array.isArray(c.label_ids) ? c.label_ids : labels.map(l => l.id);

        let tagsHtml = `<div class="wab-contact-label-tags d-flex flex-wrap gap-1 mt-1 pt-1" id="wab-contact-tags-${cleanPhone}">`;
        if (labels.length > 0) {
            tagsHtml += labels.slice(0, 4).map(lbl => `
                <span class="wab-contact-tag-chip" style="background:${lbl.color}1f;color:${lbl.color};border:1px solid ${lbl.color}4d; font-size: 11px; padding: 1.5px 6px; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                    <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:${lbl.color};"></span>${escapeHtml(lbl.name)}
                </span>
            `).join('');
            if (labels.length > 4) {
                tagsHtml += `<span class="badge bg-light text-muted border fs-9 px-1.5 py-0.5" style="font-size: 10px;">+${labels.length - 4}</span>`;
            }
        }
        tagsHtml += `</div>`;

        div.innerHTML = `
            <div class="wab-avatar" style="background:${c.color || '#25d366'}1a;color:${c.color || '#25d366'}">
                ${escapeHtml(initials(c.name))}
                <span class="wab-status-badge wab-status--${c.status || 'offline'}"></span>
            </div>
            <div class="wab-contact-info">
                <div class="wab-contact-row-top">
                    <span class="wab-contact-name">${escapeHtml(c.name || c.phone)}</span>
                    <span class="wab-contact-time">${escapeHtml(c.time || '')}</span>
                </div>
                <div class="wab-contact-row-bottom">
                    <span class="wab-contact-preview">${escapeHtml(c.msg || '')}</span>
                    <div class="wab-contact-row-right d-flex align-items-center gap-1">
                        ${c.badge ? `<span class="wab-badge">${c.badge}</span>` : ''}
                        <button type="button" class="wab-quick-tag-btn" onclick="event.stopPropagation(); openQuickLabelModal('${escapeHtml(c.phone)}', '${escapeHtml(c.name || c.phone).replace(/'/g, "\\'")}', ${JSON.stringify(labelIds)})" title="Assign Labels">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </button>
                    </div>
                </div>
                ${tagsHtml}
            </div>
        `;
        bindContactClick(div);
        return div;
    }

    /* ── Tab filter ── */
    document.querySelectorAll('.wab-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.wab-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    /* ── Real-time Debounced Search ── */
    searchInput?.addEventListener('input', function () {
        const q = this.value.trim();
        contactSearchQuery = q;
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(async () => {
            contactPage = 1;
            contactsHasMore = true;
            if (contactListLoader) contactListLoader.classList.remove('d-none');

            try {
                const url = `${contactListUrl}?page=1&search=${encodeURIComponent(q)}&active_phone=${encodeURIComponent(selectedPhone)}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;

                const data = await res.json();
                const list = document.getElementById('wabContactList');
                if (list) {
                    list.innerHTML = '';
                    (data.contacts || []).forEach(c => {
                        const el = createContactItemElement(c);
                        if (el) list.appendChild(el);
                    });
                }
                contactsHasMore = Boolean(data.has_more);
            } catch (err) {
                console.warn('Search contacts failed', err);
            } finally {
                if (contactListLoader) contactListLoader.classList.add('d-none');
            }
        }, 250);
    });

    // Handle browser Back/Forward navigation
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.phone) {
            switchChat(e.state.phone, e.state.name || e.state.phone, e.state.color || '#25d366');
        }
    });

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[char]));
    }

    function normalizeMessageStatus(status) {
        const value = String(status || 'sent').toLowerCase();

        if (['pending', 'queued', 'sending', 'sending_media', 'uploading', 'draft'].includes(value)) {
            return 'pending';
        }

        if (['read', 'seen', 'viewed', 'read_by_user', 'blue'].includes(value)) {
            return 'read';
        }

        if (['delivered', 'received'].includes(value)) {
            return 'delivered';
        }

        if (['failed', 'undelivered', 'error'].includes(value)) {
            return 'failed';
        }

        return 'sent';
    }

    function tickMarkup(status) {
        const normalized = normalizeMessageStatus(status);
        const color = normalized === 'read'
            ? '#53bdeb'
            : (['failed', 'undelivered'].includes(normalized) ? '#d93025' : '#8696a0');
        const label = normalized.charAt(0).toUpperCase() + normalized.slice(1);

        if (normalized === 'pending') {
            return `<span class="wab-tick wab-tick--pending" data-status="pending" title="Sending…">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="6.6" stroke="#8696a0" stroke-width="1.6"/>
                    <polyline points="8 4.2 8 8 10.8 8" stroke="#8696a0" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>`;
        }

        if (['failed', 'undelivered'].includes(normalized)) {
            return `<span class="wab-tick wab-tick--${normalized}" data-status="${normalized}" title="${label}">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="5.8" stroke="${color}" stroke-width="1.6"/><path d="M7 3.8v3.7" stroke="${color}" stroke-width="1.8" stroke-linecap="round"/><circle cx="7" cy="10" r="1" fill="${color}"/></svg>
            </span>`;
        }

        if (['delivered', 'read'].includes(normalized)) {
            return `<span class="wab-tick wab-tick--${normalized}" data-status="${normalized}" title="${label}">
                <svg width="18" height="12" viewBox="0 0 20 12" fill="none"><path d="M1 6.5l3.2 3.2L11.8 2" stroke="${color}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 9.7L17 1" stroke="${color}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>`;
        }

        return `<span class="wab-tick wab-tick--sent" data-status="sent" title="Sent">
            <svg width="14" height="10" viewBox="0 0 18 12" fill="none"><path d="M1 6l4 4L17 1" stroke="${color}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>`;
    }

    function updateMessageStatus(statusUpdate) {
        if (!statusUpdate) return;
        const msgId = statusUpdate.id;
        const waMsgId = statusUpdate.wa_message_id;

        let row = null;
        if (msgId) row = document.querySelector(`[data-message-id="${msgId}"]`);
        if (!row && waMsgId) row = document.querySelector(`[data-wa-message-id="${waMsgId}"]`);

        if (!row) return;

        // Ensure row has waMessageId if now available
        if (waMsgId && !row.dataset.waMessageId) {
            row.dataset.waMessageId = waMsgId;
        }

        const currentTick = row.querySelector('.wab-tick');
        const normalized = normalizeMessageStatus(statusUpdate.status);

        if (currentTick) {
            if (currentTick.dataset.status === normalized) return;
            currentTick.outerHTML = tickMarkup(normalized);
        } else {
            const meta = row.querySelector('.wab-msg-meta');
            if (meta && row.classList.contains('wab-outgoing')) {
                meta.insertAdjacentHTML('beforeend', tickMarkup(normalized));
            }
        }
    }

    function mediaTypeClass(type) {
        return ['image', 'video', 'audio', 'document'].includes(type) ? type : '';
    }

    function fileExtension(name) {
        const value = String(name || '').split('?')[0];
        const parts = value.split('.');
        return parts.length > 1 ? parts.pop().toLowerCase().slice(0, 6) : 'file';
    }

    function documentColor(ext) {
        return {
            pdf: '#ef4444',
            doc: '#2563eb',
            docx: '#2563eb',
            xls: '#16a34a',
            xlsx: '#16a34a',
            ppt: '#ea580c',
            pptx: '#ea580c',
            zip: '#7c3aed',
            rar: '#7c3aed',
        }[ext] || '#64748b';
    }

    function formatBytes(size) {
        const bytes = Number(size || 0);
        if (!bytes) return '';
        if (bytes < 1024 * 1024) return `${Math.round(bytes / 1024)} KB`;
        return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
    }

    function localDateKey(value) {
        const date = value ? new Date(String(value).replace(' ', 'T')) : new Date();
        if (Number.isNaN(date.getTime())) return '';

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function dateLabel(value) {
        const date = value ? new Date(String(value).replace(' ', 'T')) : new Date();
        if (Number.isNaN(date.getTime())) return 'Today';

        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);
        const key = localDateKey(value);

        if (key === localDateKey(today.toISOString())) return 'Today';
        if (key === localDateKey(yesterday.toISOString())) return 'Yesterday';

        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function ensureDateBadge(message) {
        if (!body) return;

        const key = localDateKey(message?.created_at);
        if (!key || body.querySelector(`.wab-date-badge[data-date-key="${key}"]`)) return;

        const badge = document.createElement('div');
        badge.className = 'wab-date-badge';
        badge.dataset.dateKey = key;
        badge.textContent = dateLabel(message?.created_at);
        insertBeforeTyping(badge);
    }

    function messageContentMarkup(message) {
        const caption = String(message.message || '').trim();

        if (!message.media_url) {
            return escapeHtml(message.message || '');
        }

        const url = escapeHtml(message.media_url);
        const name = escapeHtml(message.media_name || 'File');
        const type = mediaTypeClass(message.media_type) || 'document';
        const ext = fileExtension(message.media_name || message.media_url);
        const color = documentColor(ext);
        let mediaMarkup = '';

        if (type === 'image') {
            mediaMarkup = `<a href="${url}" target="_blank" class="wab-media-img-link" download="${name}"><img src="${url}" class="wab-media-img" alt="${name}" loading="lazy"></a>`;
        } else if (type === 'video') {
            mediaMarkup = `<video class="wab-media-video" controls preload="metadata"><source src="${url}"></video>`;
        } else if (type === 'audio') {
            const bars = Array.from({ length: 24 }, (_, i) => `<span style="height:${7 + ((i * 5) % 17)}px"></span>`).join('');
            mediaMarkup = `
                <div class="wab-voice-card">
                    <button type="button" class="wab-voice-play" title="Play audio">
                        <svg class="wab-voice-play-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        <svg class="wab-voice-pause-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" hidden><path d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg>
                    </button>
                    <div class="wab-voice-wave">${bars}</div>
                    <span class="wab-voice-time">0:00</span>
                    <audio class="wab-voice-audio" preload="metadata" src="${url}"></audio>
                </div>
            `;
        } else {
            mediaMarkup = `
                <a href="${url}" target="_blank" class="wab-media-doc-card" download="${name}">
                    <div class="wab-media-doc-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span class="wab-doc-ext" style="color:${color}">${escapeHtml(ext.toUpperCase())}</span>
                    </div>
                    <div class="wab-media-doc-info">
                        <span class="wab-media-doc-name">${name}</span>
                        <span class="wab-media-doc-size">${escapeHtml(formatBytes(message.media_size))}</span>
                    </div>
                    <svg class="wab-doc-dl" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8696a0" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </a>
            `;
        }

        return mediaMarkup + (caption ? `<div class="wab-media-caption">${escapeHtml(message.message)}</div>` : '');
    }

    async function fetchOlderMessages() {
        if (!selectedPhone || !hasMoreOlder || isLoadingOlder || firstMessageId <= 0) return;
        isLoadingOlder = true;
        if (olderLoader) olderLoader.classList.remove('d-none');

        try {
            const url = `${messagesUrl}?phone=${encodeURIComponent(selectedPhone)}&before_id=${firstMessageId}&limit=25`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;

            const data = await res.json();
            const olderList = data.messages || [];

            if (olderList.length === 0) {
                hasMoreOlder = false;
                if (olderLoader) olderLoader.classList.add('d-none');
                return;
            }

            // Record previous scroll metrics to maintain smooth reading position
            const prevScrollHeight = body.scrollHeight;
            const prevScrollTop = body.scrollTop;

            renderOlderMessagesBatch(olderList);

            firstMessageId = Number(data.first_id || olderList[0]?.id || firstMessageId);
            body.dataset.firstMessageId = String(firstMessageId);
            hasMoreOlder = Boolean(data.has_more_older);

            if (!hasMoreOlder && olderLoader) {
                olderLoader.classList.add('d-none');
            }

            // Restore scroll position
            const newScrollHeight = body.scrollHeight;
            body.scrollTop = prevScrollTop + (newScrollHeight - prevScrollHeight);
        } catch (err) {
            console.warn('Error fetching older WhatsApp messages:', err);
        } finally {
            isLoadingOlder = false;
            if (!hasMoreOlder && olderLoader) olderLoader.classList.add('d-none');
        }
    }

    function renderOlderMessagesBatch(messages) {
        if (!body || !Array.isArray(messages) || messages.length === 0) return;

        const frag = document.createDocumentFragment();
        let lastDate = null;

        messages.forEach(message => {
            if (!message?.id || body.querySelector(`[data-message-id="${message.id}"]`)) return;

            const key = localDateKey(message.created_at);
            if (key && key !== lastDate) {
                const badge = document.createElement('div');
                badge.className = 'wab-date-badge';
                badge.dataset.dateKey = key;
                badge.textContent = dateLabel(message.created_at);
                frag.appendChild(badge);
                lastDate = key;
            }

            const hasMedia = Boolean(message.media_url);
            const mediaClass = mediaTypeClass(message.media_type);
            const row = document.createElement('div');
            row.className = `wab-msg-row ${message.direction === 'inbound' ? 'wab-incoming' : 'wab-outgoing'}`;
            row.dataset.messageId = message.id;
            if (message.wa_message_id) row.dataset.waMessageId = message.wa_message_id;
            row.innerHTML = `
                <div class="wab-msg-bubble ${hasMedia ? `wab-bubble--media ${mediaClass ? `wab-bubble--${mediaClass}` : ''}` : ''}">
                    ${messageContentMarkup(message)}
                    <div class="wab-msg-meta">
                        <span class="wab-msg-time">${escapeHtml(message.time || '')}</span>
                        ${message.direction === 'outbound' ? tickMarkup(message.status) : ''}
                    </div>
                </div>
            `;
            frag.appendChild(row);
            initVoiceCards(row);
        });

        // Insert after olderLoader (or at start of body)
        if (olderLoader && olderLoader.parentNode === body && olderLoader.nextSibling) {
            body.insertBefore(frag, olderLoader.nextSibling);
        } else {
            body.prepend(frag);
        }
    }

    function renderMessage(message) {
        const hasText = String(message?.message || '').trim() !== '';
        const hasMedia = Boolean(message?.media_url);

        if (!body || (!hasText && !hasMedia) || document.querySelector(`[data-message-id="${message.id}"]`)) {
            return;
        }

        document.querySelector('.wab-empty-message')?.remove();
        ensureDateBadge(message);

        const mediaClass = mediaTypeClass(message.media_type);
        const row = document.createElement('div');
        row.className = `wab-msg-row ${message.direction === 'inbound' ? 'wab-incoming' : 'wab-outgoing'}`;
        row.dataset.messageId = message.id;
        if (message.wa_message_id) row.dataset.waMessageId = message.wa_message_id;
        row.innerHTML = `
            <div class="wab-msg-bubble ${hasMedia ? `wab-bubble--media ${mediaClass ? `wab-bubble--${mediaClass}` : ''}` : ''}">
                ${messageContentMarkup(message)}
                <div class="wab-msg-meta">
                    <span class="wab-msg-time">${escapeHtml(message.time || '')}</span>
                    ${message.direction === 'outbound' ? tickMarkup(message.status) : ''}
                </div>
            </div>
        `;

        insertBeforeTyping(row);
        initVoiceCards(row);
        const numericId = Number(message.id);
        if (!Number.isNaN(numericId) && numericId > 0) {
            lastMessageId = Math.max(lastMessageId, numericId);
            body.dataset.lastMessageId = String(lastMessageId);
            if (firstMessageId === 0) {
                firstMessageId = numericId;
                body.dataset.firstMessageId = String(firstMessageId);
            }
        }
        body.scrollTop = body.scrollHeight;
    }

    function initVoiceCards(root = document) {
        root.querySelectorAll('.wab-voice-card').forEach(card => {
            if (card.dataset.voiceReady === '1') return;
            card.dataset.voiceReady = '1';

            const audio = card.querySelector('.wab-voice-audio');
            const time = card.querySelector('.wab-voice-time');
            const playIcon = card.querySelector('.wab-voice-play-icon');
            const pauseIcon = card.querySelector('.wab-voice-pause-icon');

            const setTime = seconds => {
                if (time) time.textContent = formatAudioTime(seconds);
            };

            audio?.addEventListener('loadedmetadata', () => {
                if (Number.isFinite(audio.duration)) setTime(audio.duration);
            });

            audio?.addEventListener('timeupdate', () => {
                if (!audio.paused) setTime(audio.currentTime);
            });

            audio?.addEventListener('ended', () => {
                card.classList.remove('is-playing');
                playIcon?.removeAttribute('hidden');
                pauseIcon?.setAttribute('hidden', 'hidden');
                if (Number.isFinite(audio.duration)) setTime(audio.duration);
            });
        });
    }

    body?.addEventListener('click', event => {
        const button = event.target.closest('.wab-voice-play');
        if (!button) return;

        const card = button.closest('.wab-voice-card');
        const audio = card?.querySelector('.wab-voice-audio');
        if (!card || !audio) return;

        document.querySelectorAll('.wab-voice-audio').forEach(other => {
            if (other !== audio) {
                other.pause();
                const otherCard = other.closest('.wab-voice-card');
                otherCard?.classList.remove('is-playing');
                otherCard?.querySelector('.wab-voice-play-icon')?.removeAttribute('hidden');
                otherCard?.querySelector('.wab-voice-pause-icon')?.setAttribute('hidden', 'hidden');
            }
        });

        if (audio.paused) {
            audio.play();
            card.classList.add('is-playing');
            card.querySelector('.wab-voice-play-icon')?.setAttribute('hidden', 'hidden');
            card.querySelector('.wab-voice-pause-icon')?.removeAttribute('hidden');
            return;
        }

        audio.pause();
        card.classList.remove('is-playing');
        card.querySelector('.wab-voice-play-icon')?.removeAttribute('hidden');
        card.querySelector('.wab-voice-pause-icon')?.setAttribute('hidden', 'hidden');
    });

    function contactUrl(phone) {
        return @json(route('whatsapp.chat')) + '?phone=' + encodeURIComponent(phone);
    }

    function initials(name) {
        return String(name || '?').trim().charAt(0).toUpperCase() || '?';
    }

    function updateBadge(item, badgeCount) {
        const right = item.querySelector('.wab-contact-row-right');
        let badge = item.querySelector('.wab-badge');
        const count = Number(badgeCount || 0);
        item.dataset.badge = String(count);

        if (count > 0) {
            if (!badge && right) {
                badge = document.createElement('span');
                badge.className = 'wab-badge';
                right.prepend(badge);
            }
            if (badge) badge.textContent = count;
        } else {
            badge?.remove();
        }
    }

    function renderContact(contact) {
        const list = document.getElementById('wabContactList');
        if (!list || !contact?.phone) return;
        const cleanPhone = String(contact.phone).replace(/\D+/g, '');

        let item = Array.from(list.querySelectorAll('.wab-contact-item')).find(row => row.dataset.phone === contact.phone);
        const color = contact.color || '#25d366';
        const labels = Array.isArray(contact.labels) ? contact.labels : [];
        const labelIds = Array.isArray(contact.label_ids) ? contact.label_ids : labels.map(l => l.id);

        if (!item) {
            item = document.createElement('div');
            item.className = 'wab-contact-item';
            item.id = `wab-contact-card-${cleanPhone}`;
            item.dataset.contactId = contact.id || '';
            item.dataset.phone = contact.phone;
            item.dataset.color = color;
            item.dataset.badge = String(contact.badge || 0);
            item.dataset.isGroup = contact.is_group ? '1' : '0';
            item.dataset.url = contactUrl(contact.phone);
            item.innerHTML = `
                <div class="wab-avatar" style="background:${color}1a;color:${color}">
                    <span class="wab-avatar-letter">${escapeHtml(initials(contact.name))}</span>
                    <span class="wab-status-badge wab-status--offline"></span>
                </div>
                <div class="wab-contact-info">
                    <div class="wab-contact-row-top">
                        <span class="wab-contact-name"></span>
                        <span class="wab-contact-time"></span>
                    </div>
                    <div class="wab-contact-row-bottom">
                        <span class="wab-contact-preview"></span>
                        <div class="wab-contact-row-right d-flex align-items-center gap-1">
                            <button type="button" class="wab-quick-tag-btn" onclick="event.stopPropagation(); openQuickLabelModal('${escapeHtml(contact.phone)}', '${escapeHtml(contact.name || contact.phone).replace(/'/g, "\\'")}', ${JSON.stringify(labelIds)})" title="Assign Labels">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="wab-contact-label-tags d-flex flex-wrap gap-1 mt-1 pt-1" id="wab-contact-tags-${cleanPhone}"></div>
                </div>
            `;
            bindContactClick(item);
            list.prepend(item);
        }

        item.dataset.name = String(contact.name || '').toLowerCase();
        item.dataset.badge = String(contact.badge || 0);
        if (contact.is_group !== undefined) {
            item.dataset.isGroup = contact.is_group ? '1' : '0';
        }
        item.dataset.url = contactUrl(contact.phone);
        item.classList.toggle('is-active', contact.phone === selectedPhone);
        item.querySelector('.wab-contact-name').textContent = contact.name || contact.phone;
        item.querySelector('.wab-contact-time').textContent = contact.time || '';
        item.querySelector('.wab-contact-preview').textContent = contact.msg || '';
        item.querySelector('.wab-avatar').style.background = `${color}1a`;
        item.querySelector('.wab-avatar').style.color = color;
        const letter = item.querySelector('.wab-avatar-letter');
        if (letter) letter.textContent = initials(contact.name);
        updateBadge(item, Number(contact.badge || 0));

        // Update tag button onclick if contact has label info
        const tagBtn = item.querySelector('.wab-quick-tag-btn');
        if (tagBtn && contact.name) {
            tagBtn.setAttribute('onclick', `event.stopPropagation(); openQuickLabelModal('${escapeHtml(contact.phone)}', '${escapeHtml(contact.name).replace(/'/g, "\\'")}', ${JSON.stringify(labelIds)})`);
        }

        // Update label chips if provided in payload
        if (labels.length > 0) {
            const tagsWrap = item.querySelector('.wab-contact-label-tags');
            if (tagsWrap) {
                let chipsHtml = labels.slice(0, 4).map(lbl => `
                    <span class="wab-contact-tag-chip" style="background:${lbl.color}1f;color:${lbl.color};border:1px solid ${lbl.color}4d; font-size: 11px; padding: 1.5px 6px; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                        <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:${lbl.color};"></span>${escapeHtml(lbl.name)}
                    </span>
                `).join('');
                if (labels.length > 4) {
                    chipsHtml += `<span class="badge bg-light text-muted border fs-9 px-1.5 py-0.5" style="font-size: 10px;">+${labels.length - 4}</span>`;
                }
                tagsWrap.innerHTML = chipsHtml;
            }
        }
        
        if (list.firstChild !== item) {
            list.prepend(item);
        }
    }

    let currentTabFilter = 'all';

    function filterContactList() {
        const list = document.getElementById('wabContactList');
        if (!list) return;

        const term = (searchInput?.value || '').trim().toLowerCase();
        const items = list.querySelectorAll('.wab-contact-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = (item.dataset.name || item.querySelector('.wab-contact-name')?.textContent || '').toLowerCase();
            const phone = (item.dataset.phone || '').toLowerCase();
            const badge = Number(item.dataset.badge || item.querySelector('.wab-badge')?.textContent || 0);
            const isGroup = item.dataset.isGroup === '1' || name.includes('group') || (item.querySelector('.wab-contact-tag-chip')?.textContent || '').toLowerCase().includes('group');

            const matchesSearch = !term || name.includes(term) || phone.includes(term);
            let matchesTab = true;

            if (currentTabFilter === 'unread') {
                matchesTab = badge > 0;
            } else if (currentTabFilter === 'groups') {
                matchesTab = isGroup;
            }

            if (matchesSearch && matchesTab) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        let emptyEl = list.querySelector('.wab-tab-empty-msg');
        if (visibleCount === 0) {
            if (!emptyEl) {
                emptyEl = document.createElement('div');
                emptyEl.className = 'wab-tab-empty-msg';
                emptyEl.style.cssText = 'text-align:center;padding:28px 16px;color:#8696a0;font-size:13px;font-weight:500;';
                list.appendChild(emptyEl);
            }
            emptyEl.style.display = 'block';
            if (currentTabFilter === 'unread') {
                emptyEl.textContent = 'No unread chats found.';
            } else if (currentTabFilter === 'groups') {
                emptyEl.textContent = 'No group chats found.';
            } else {
                emptyEl.textContent = 'No conversations found.';
            }
        } else if (emptyEl) {
            emptyEl.style.display = 'none';
        }
    }

    function updateContacts(contacts) {
        if (!Array.isArray(contacts)) return;
        contacts.slice().reverse().forEach(renderContact);
        filterContactList();
    }

    function setRemoteTyping(isTyping) {
        const row = getTypingRow();
        if (!row) return;

        if (isTyping) {
            row.hidden = false;
            row.classList.add('is-visible');
            if (typingLabel) typingLabel.textContent = 'typing...';
            body.scrollTop = body.scrollHeight;
            return;
        }

        row.classList.remove('is-visible');
        row.hidden = true;
        if (typingLabel) typingLabel.textContent = defaultTypingLabel;
    }

    async function markSelectedChatRead() {
        if (!selectedPhone) return;

        try {
            const response = await fetch(markReadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ phone: selectedPhone }),
            });
            if (!response.ok) return;
            const data = await response.json();
            updateContacts(data.contacts);
        } catch (error) {
            console.warn('Unable to mark WhatsApp messages as read.', error);
        }
    }

    async function pollMessages() {
        if (!selectedPhone || isPolling) return;
        isPolling = true;

        try {
            const safeAfterId = Number.isInteger(Number(lastMessageId)) && Number(lastMessageId) > 0 ? Number(lastMessageId) : 0;
            const url = `${messagesUrl}?phone=${encodeURIComponent(selectedPhone)}&after_id=${safeAfterId}`;
            const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) return;

            const data = await response.json();
            (data.messages || []).forEach(renderMessage);
            (data.statuses || []).forEach(updateMessageStatus);
            updateContacts(data.contacts);
            setRemoteTyping(Boolean(data.typing));
        } catch (error) {
            console.warn('Unable to refresh WhatsApp chat.', error);
        } finally {
            isPolling = false;
        }
    }

    /* ── Emoji panel ── */
    emojiBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        emojiPanel.classList.toggle('is-open');
    });
    document.addEventListener('click', () => emojiPanel.classList.remove('is-open'));
    emojiPanel?.addEventListener('click', e => e.stopPropagation());

    document.querySelectorAll('.wab-emoji-item').forEach(btn => {
        btn.addEventListener('click', () => {
            input.value += btn.dataset.emoji;
            input.focus();
            emojiPanel.classList.remove('is-open');
        });
    });

    /* ── Send message ── */
    function formatAudioTime(seconds) {
        const total = Math.max(0, Math.floor(Number(seconds || 0)));
        return `${Math.floor(total / 60)}:${String(total % 60).padStart(2, '0')}`;
    }

    function setAudioTime(seconds) {
        if (audioTime) audioTime.textContent = formatAudioTime(seconds);
    }

    function setAudioUi(state) {
        sendForm?.classList.toggle('is-audio-recording', state === 'recording');
        sendForm?.classList.toggle('is-audio-ready', state === 'ready');
        if (audioReview) audioReview.hidden = !['recording', 'ready'].includes(state);
        micBtn?.classList.toggle('is-recording', state === 'recording');
        micBtn?.querySelector('.wab-mic-icon')?.toggleAttribute('hidden', state === 'recording');
        micBtn?.querySelector('.wab-stop-icon')?.toggleAttribute('hidden', state !== 'recording');
        if (input) input.disabled = state === 'recording' || state === 'ready';
    }

    function resetAudioRecording() {
        if (audioTimer) clearInterval(audioTimer);
        audioTimer = null;
        audioChunks = [];
        recordedAudioBlob = null;
        recordedAudioDuration = 0;
        if (recordedAudio) {
            recordedAudio.pause();
            recordedAudio = null;
        }
        if (recordedAudioUrl) URL.revokeObjectURL(recordedAudioUrl);
        recordedAudioUrl = '';
        audioStream?.getTracks().forEach(track => track.stop());
        audioStream = null;
        if (audioPlay) {
            audioPlay.querySelector('.wab-audio-play-icon')?.removeAttribute('hidden');
            audioPlay.querySelector('.wab-audio-pause-icon')?.setAttribute('hidden', 'hidden');
        }
        audioWave?.classList.remove('is-playing');
        setAudioTime(0);
        setAudioUi('idle');
        if (input) input.disabled = false;
    }

    async function startAudioRecording() {
        if (!selectedPhone || !navigator.mediaDevices?.getUserMedia || typeof MediaRecorder === 'undefined') {
            alert('Audio recording is not supported in this browser.');
            return;
        }

        try {
            resetAudioRecording();
            audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const recorderOptions = [
                { mimeType: 'audio/ogg;codecs=opus', extension: 'ogg' },
                { mimeType: 'audio/mp4', extension: 'm4a' },
                { mimeType: 'audio/webm;codecs=opus', extension: 'webm' },
                { mimeType: 'audio/webm', extension: 'webm' },
            ].find(option => MediaRecorder.isTypeSupported(option.mimeType)) || { mimeType: '', extension: 'webm' };
            recordedAudioExtension = recorderOptions.extension;
            mediaRecorder = recorderOptions.mimeType
                ? new MediaRecorder(audioStream, { mimeType: recorderOptions.mimeType })
                : new MediaRecorder(audioStream);
            audioStartedAt = Date.now();
            setAudioTime(0);
            setAudioUi('recording');

            mediaRecorder.addEventListener('dataavailable', event => {
                if (event.data?.size) audioChunks.push(event.data);
            });

            mediaRecorder.addEventListener('stop', () => {
                if (audioTimer) clearInterval(audioTimer);
                audioTimer = null;
                audioStream?.getTracks().forEach(track => track.stop());
                audioStream = null;
                recordedAudioDuration = Math.max(1, Math.round((Date.now() - audioStartedAt) / 1000));
                recordedAudioBlob = new Blob(audioChunks, { type: mediaRecorder.mimeType || recorderOptions.mimeType || 'audio/webm' });
                recordedAudioUrl = URL.createObjectURL(recordedAudioBlob);
                recordedAudio = new Audio(recordedAudioUrl);
                recordedAudio.addEventListener('ended', () => {
                    audioWave?.classList.remove('is-playing');
                    audioPlay?.querySelector('.wab-audio-play-icon')?.removeAttribute('hidden');
                    audioPlay?.querySelector('.wab-audio-pause-icon')?.setAttribute('hidden', 'hidden');
                    setAudioTime(recordedAudioDuration);
                });
                setAudioTime(recordedAudioDuration);
                setAudioUi('ready');
            });

            mediaRecorder.start();
            audioTimer = setInterval(() => setAudioTime((Date.now() - audioStartedAt) / 1000), 250);
        } catch (error) {
            console.warn('Unable to start audio recording.', error);
            resetAudioRecording();
            alert('Microphone permission nahi mili. Browser permission allow karke dobara try karo.');
        }
    }

    function stopAudioRecording() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
        }
    }

    async function sendRecordedAudio() {
        if (!recordedAudioBlob || !selectedPhone) return false;

        const now = new Date();
        const nowTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
        const tempId = 'temp_audio_' + Date.now();
        const audioBlobToUpload = recordedAudioBlob;
        const audioExt = recordedAudioExtension;
        const localAudioUrl = recordedAudioUrl || URL.createObjectURL(audioBlobToUpload);

        // 1. Optimistic instant render with clock icon (🕒)
        const optimisticAudio = {
            id: tempId,
            phone: selectedPhone,
            message: '',
            direction: 'outbound',
            status: 'pending', // 🕒 Clock icon
            time: nowTime,
            created_at: now.toISOString(),
            media_url: localAudioUrl,
            media_type: 'audio',
            media_name: `voice-note-${Date.now()}.${audioExt}`,
            media_size: audioBlobToUpload.size,
        };

        renderMessage(optimisticAudio);
        resetAudioRecording();

        const formData = new FormData();
        formData.append('phone', selectedPhone);
        formData.append('caption', '');
        formData.append('files[]', audioBlobToUpload, `voice-note-${Date.now()}.${audioExt}`);

        try {
            const response = await fetch(mediaUploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await response.json().catch(() => ({}));
            const tempRow = document.querySelector(`[data-message-id="${tempId}"]`);

            if (!response.ok) {
                if (tempRow) updateMessageStatus({ id: tempId, status: 'failed' });
                if (data.contacts) updateContacts(data.contacts);
                throw new Error(data.error || data.message || 'Audio send failed');
            }

            const returnedMsgs = Array.isArray(data.messages) ? data.messages : (data.message ? [data.message] : []);
            if (returnedMsgs.length > 0) {
                const msg = returnedMsgs[0];
                if (tempRow) {
                    tempRow.dataset.messageId = msg.id;
                    if (msg.wa_message_id) tempRow.dataset.waMessageId = msg.wa_message_id;
                    updateMessageStatus(msg);
                } else {
                    renderMessage(msg);
                }
                const realMsgId = Number(msg.id);
                if (!Number.isNaN(realMsgId) && realMsgId > 0) {
                    lastMessageId = Math.max(lastMessageId, realMsgId);
                    if (body) body.dataset.lastMessageId = String(lastMessageId);
                }
            }

            if (data.contacts) updateContacts(data.contacts);
            return true;
        } catch (error) {
            console.warn('Unable to send audio recording.', error);
            const tempRow = document.querySelector(`[data-message-id="${tempId}"]`);
            if (tempRow) updateMessageStatus({ id: tempId, status: 'failed' });
            alert(error.message || 'Audio send nahi ho paya. Please dobara try karo.');
            return false;
        }
    }

    micBtn?.addEventListener('click', () => {
        if (mediaRecorder?.state === 'recording') {
            stopAudioRecording();
            return;
        }

        startAudioRecording();
    });

    audioDelete?.addEventListener('click', resetAudioRecording);

    audioPlay?.addEventListener('click', () => {
        if (!recordedAudio) return;

        if (recordedAudio.paused) {
            recordedAudio.currentTime = 0;
            recordedAudio.play();
            audioWave?.classList.add('is-playing');
            audioPlay.querySelector('.wab-audio-play-icon')?.setAttribute('hidden', 'hidden');
            audioPlay.querySelector('.wab-audio-pause-icon')?.setAttribute('hidden', 'hidden');
            setAudioTime(recordedAudioDuration);
            return;
        }

        recordedAudio.pause();
        audioWave?.classList.remove('is-playing');
        audioPlay.querySelector('.wab-audio-play-icon')?.removeAttribute('hidden');
        audioPlay.querySelector('.wab-audio-pause-icon')?.setAttribute('hidden', 'hidden');
    });

    async function submitCurrentMessage(event) {
        event?.preventDefault();

        if (!sendForm || !input || !selectedPhone) return;

        if (recordedAudioBlob) {
            await sendRecordedAudio();
            return;
        }

        const text = input.value.trim();
        if (!text) {
            input.focus();
            return;
        }

        // 1. Instant 0ms Optimistic UI: Render bubble immediately with WhatsApp Clock / Watch icon (🕒)
        const tempId = 'temp_' + Date.now();
        const now = new Date();
        const nowTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

        const optimisticMsg = {
            id: tempId,
            phone: selectedPhone,
            message: text,
            direction: 'outbound',
            status: 'pending', // 🕒 Clock icon
            time: nowTime,
            created_at: now.toISOString(),
        };

        renderMessage(optimisticMsg);

        // Instantly update sidebar contact preview & move to top
        renderContact({
            phone: selectedPhone,
            msg: text,
            time: nowTime,
            badge: 0,
        });

        // Clear input immediately so user can immediately type the next message
        const originalText = text;
        const formData = new FormData(sendForm);
        formData.set('message', text);
        input.value = '';
        input.focus();
        setRemoteTyping(false);

        // 2. Perform network request in background
        try {
            const response = await fetch(sendForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await response.json().catch(() => ({}));
            const tempRow = document.querySelector(`[data-message-id="${tempId}"]`);

            if (!response.ok) {
                if (tempRow) {
                    updateMessageStatus({ id: tempId, status: 'failed' });
                }
                if (data.contacts) updateContacts(data.contacts);
                throw new Error(data.error || data.message || 'Send failed');
            }

            if (data.message) {
                if (tempRow) {
                    tempRow.dataset.messageId = data.message.id;
                    if (data.message.wa_message_id) tempRow.dataset.waMessageId = data.message.wa_message_id;
                    updateMessageStatus(data.message);
                } else {
                    renderMessage(data.message);
                }
                const realMsgId = Number(data.message.id);
                if (!Number.isNaN(realMsgId) && realMsgId > 0) {
                    lastMessageId = Math.max(lastMessageId, realMsgId);
                    if (body) body.dataset.lastMessageId = String(lastMessageId);
                }
            }

            if (data.contacts) updateContacts(data.contacts);
        } catch (error) {
            console.warn('Unable to send WhatsApp message.', error);
            const tempRow = document.querySelector(`[data-message-id="${tempId}"]`);
            if (tempRow) {
                updateMessageStatus({ id: tempId, status: 'failed' });
            }
        }
    }

    function closeAttachMenu() {
        attachMenu?.classList.remove('is-open');
        attachBtn?.classList.remove('is-open');
    }

    function getUploadType(file) {
        const mime = file?.type || '';
        if (mime.startsWith('image/')) return 'image';
        if (mime.startsWith('video/')) return 'video';
        if (mime.startsWith('audio/')) return 'audio';
        return 'document';
    }

    function renderUploadPreview(index = activeUploadIndex) {
        const file = selectedUploadFiles[index];
        const url = selectedUploadUrls[index];
        if (!file || !url) return;

        activeUploadIndex = index;
        const type = getUploadType(file);
        const ext = fileExtension(file.name);
        const color = documentColor(ext);
        if (uploadFileName) uploadFileName.textContent = selectedUploadFiles.length > 1
            ? `${file.name} (${index + 1}/${selectedUploadFiles.length})`
            : file.name;

        if (uploadPreview) {
            if (type === 'image') {
                uploadPreview.innerHTML = `<img src="${url}" alt="${escapeHtml(file.name)}">`;
            } else if (type === 'video') {
                uploadPreview.innerHTML = `<video controls preload="metadata"><source src="${url}"></video>`;
            } else {
                uploadPreview.innerHTML = `
                    <div class="wab-upload-doc-preview">
                        <div>
                            <svg width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <strong>No preview available</strong>
                            <span>${escapeHtml(formatBytes(file.size))}${formatBytes(file.size) ? ' - ' : ''}${escapeHtml(ext.toUpperCase())}</span>
                        </div>
                    </div>
                `;
                const icon = uploadPreview.querySelector('svg');
                if (icon) icon.style.color = color;
            }
        }

        uploadTray?.querySelectorAll('.wab-upload-thumb').forEach((thumb, thumbIndex) => {
            thumb.classList.toggle('is-active', thumbIndex === index);
        });
    }

    function removeUploadFile(index) {
        if (index < 0 || index >= selectedUploadFiles.length) return;

        if (selectedUploadUrls[index]) {
            URL.revokeObjectURL(selectedUploadUrls[index]);
        }

        selectedUploadFiles.splice(index, 1);
        selectedUploadUrls.splice(index, 1);

        if (selectedUploadFiles.length === 0) {
            closeUploadReview();
            return;
        }

        if (activeUploadIndex >= selectedUploadFiles.length) {
            activeUploadIndex = selectedUploadFiles.length - 1;
        } else if (activeUploadIndex === index) {
            activeUploadIndex = Math.min(index, selectedUploadFiles.length - 1);
        }

        renderUploadTray();
        renderUploadPreview(activeUploadIndex);
    }

    function renderUploadTray() {
        if (!uploadTray) return;

        uploadTray.innerHTML = selectedUploadFiles.map((file, index) => {
            const url = selectedUploadUrls[index];
            const type = getUploadType(file);
            const ext = escapeHtml(fileExtension(file.name).toUpperCase());
            let thumbContent = '';

            if (type === 'image') {
                thumbContent = `<img src="${url}" alt="${escapeHtml(file.name)}">`;
            } else if (type === 'video') {
                thumbContent = `<video muted><source src="${url}"></video>`;
            } else {
                thumbContent = `<span>${ext}</span>`;
            }

            return `
                <div class="wab-upload-thumb-wrap ${index === activeUploadIndex ? 'is-active' : ''}">
                    <button type="button" class="wab-upload-thumb" data-upload-index="${index}">
                        ${thumbContent}
                    </button>
                    <button type="button" class="wab-upload-thumb-del" data-remove-index="${index}" title="Remove this file">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            `;
        }).join('') + (selectedUploadFiles.length > 1 ? `<span class="wab-upload-count">${selectedUploadFiles.length}</span>` : '');
    }

    function openUploadReview(files) {
        const validFiles = Array.from(files || []).filter(Boolean);
        if (!validFiles.length || !selectedPhone) return;

        const oversize = validFiles.find(file => file.size > 50 * 1024 * 1024);
        if (oversize) {
            alert(`${oversize.name} size must be 50 MB or less.`);
            return;
        }

        selectedUploadUrls.forEach(url => URL.revokeObjectURL(url));
        selectedUploadFiles = validFiles;
        selectedUploadUrls = validFiles.map(file => URL.createObjectURL(file));
        activeUploadIndex = 0;

        if (uploadCaption) uploadCaption.value = input?.value.trim() || '';
        renderUploadTray();
        renderUploadPreview(0);
        closeAttachMenu();
        if (uploadReview) uploadReview.hidden = false;
        uploadCaption?.focus();
    }

    function closeUploadReview() {
        selectedUploadUrls.forEach(url => URL.revokeObjectURL(url));
        selectedUploadFiles = [];
        selectedUploadUrls = [];
        activeUploadIndex = 0;
        if (uploadReview) uploadReview.hidden = true;
        if (uploadPreview) uploadPreview.innerHTML = '';
        if (uploadTray) uploadTray.innerHTML = '';
        if (uploadCaption) uploadCaption.value = '';
        if (documentInput) documentInput.value = '';
        if (photoVideoInput) photoVideoInput.value = '';
        input?.focus();
    }

    async function uploadSelectedMedia(files) {
        const uploadFiles = Array.from(files || []).filter(Boolean);
        if (!uploadFiles.length || !selectedPhone) return;

        const caption = uploadCaption?.value.trim() || '';
        const now = new Date();
        const nowTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

        // 1. Instant Optimistic Render for each file in chat window
        const tempIds = [];
        uploadFiles.forEach((file, idx) => {
            const tempId = 'temp_upload_' + Date.now() + '_' + idx;
            tempIds.push(tempId);
            const type = getUploadType(file);
            const localBlobUrl = URL.createObjectURL(file);

            const optimisticMedia = {
                id: tempId,
                phone: selectedPhone,
                message: idx === 0 ? caption : '',
                direction: 'outbound',
                status: 'pending', // 🕒 Clock / Watch icon
                time: nowTime,
                created_at: now.toISOString(),
                media_url: localBlobUrl,
                media_type: type,
                media_name: file.name,
                media_size: file.size,
            };

            renderMessage(optimisticMedia);
        });

        // Close upload review instantly so UI is not blocked
        closeUploadReview();
        if (input) input.value = '';

        const formData = new FormData();
        formData.append('phone', selectedPhone);
        uploadFiles.forEach(file => formData.append('files[]', file));
        if (caption) formData.append('caption', caption);

        try {
            const response = await fetch(mediaUploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                tempIds.forEach(tId => updateMessageStatus({ id: tId, status: 'failed' }));
                if (data.contacts) updateContacts(data.contacts);
                throw new Error(data.error || data.message || 'Media upload failed');
            }

            const returnedMsgs = Array.isArray(data.messages) ? data.messages : (data.message ? [data.message] : []);
            returnedMsgs.forEach((msg, idx) => {
                const tId = tempIds[idx];
                const tempRow = tId ? document.querySelector(`[data-message-id="${tId}"]`) : null;
                if (tempRow) {
                    tempRow.dataset.messageId = msg.id;
                    if (msg.wa_message_id) tempRow.dataset.waMessageId = msg.wa_message_id;
                    updateMessageStatus(msg);
                } else {
                    renderMessage(msg);
                }
                const realMsgId = Number(msg.id);
                if (!Number.isNaN(realMsgId) && realMsgId > 0) {
                    lastMessageId = Math.max(lastMessageId, realMsgId);
                    if (body) body.dataset.lastMessageId = String(lastMessageId);
                }
            });

            if (data.contacts) updateContacts(data.contacts);
        } catch (error) {
            console.warn('Unable to send WhatsApp media.', error);
            tempIds.forEach(tId => updateMessageStatus({ id: tId, status: 'failed' }));
            alert(error.message || 'Unable to send media. Please try again.');
        }
    }

    sendForm?.addEventListener('submit', submitCurrentMessage);
    sendBtn?.addEventListener('click', e => { if (!recordedAudioBlob && !input?.value.trim()) e.preventDefault(); });
    attachBtn?.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        if (!selectedPhone) return;
        attachMenu?.classList.toggle('is-open');
        attachBtn.classList.toggle('is-open');
    });
    attachMenu?.addEventListener('click', e => {
        e.stopPropagation();
        const action = e.target.closest('[data-attach-type]')?.dataset.attachType;
        if (action === 'document') documentInput?.click();
        if (action === 'media') photoVideoInput?.click();
    });
    document.addEventListener('click', closeAttachMenu);
    documentInput?.addEventListener('change', () => {
        if (documentInput.files?.length) openUploadReview(documentInput.files);
    });
    photoVideoInput?.addEventListener('change', () => {
        if (photoVideoInput.files?.length) openUploadReview(photoVideoInput.files);
    });
    uploadClose?.addEventListener('click', closeUploadReview);
    uploadTray?.addEventListener('click', e => {
        const delBtn = e.target.closest('.wab-upload-thumb-del');
        if (delBtn) {
            e.stopPropagation();
            const removeIdx = Number(delBtn.dataset.removeIndex);
            if (!Number.isNaN(removeIdx)) {
                removeUploadFile(removeIdx);
            }
            return;
        }

        const thumbBtn = e.target.closest('[data-upload-index]');
        if (thumbBtn) {
            const index = Number(thumbBtn.dataset.uploadIndex);
            if (!Number.isNaN(index)) renderUploadPreview(index);
        }
    });
    uploadSend?.addEventListener('click', () => uploadSelectedMedia(selectedUploadFiles));
    uploadCaption?.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            uploadSelectedMedia(selectedUploadFiles);
        }
    });
    input?.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendForm?.requestSubmit(); } });

    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if (uploadReview && !uploadReview.hidden) {
            closeUploadReview();
            return;
        }
        if (document.querySelector('.modal.show')) return;
        if (document.body.classList.contains('wab-profile-open')) return;
        if (window.innerWidth <= 768 && sidebar?.classList.contains('mobile-hidden')) {
            sidebar.classList.remove('mobile-hidden');
            return;
        }
        @if($selectedPhone)
            window.location.href = '{{ route('whatsapp.chat') }}';
        @endif
    });

    function toggleSidebarCollapse(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const pageEl = document.getElementById('wabPage');
        const sidebarEl = document.getElementById('wabSidebar');

        if (window.innerWidth <= 768) {
            sidebarEl?.classList.toggle('mobile-hidden');
        } else {
            pageEl?.classList.toggle('sidebar-collapsed');
            sidebarEl?.classList.toggle('is-collapsed');
        }
    }
    window.wabToggleSidebar = toggleSidebarCollapse;

    document.querySelectorAll('.wab-tab').forEach(tabBtn => {
        tabBtn.addEventListener('click', () => {
            document.querySelectorAll('.wab-tab').forEach(b => b.classList.remove('active'));
            tabBtn.classList.add('active');
            currentTabFilter = tabBtn.dataset.tab || 'all';
            filterContactList();
        });
    });

    searchInput?.addEventListener('input', filterContactList);

    async function refreshContactsSidebar() {
        if (contactSearchQuery !== '') return;
        try {
            const url = `${contactListUrl}?page=1&active_phone=${encodeURIComponent(selectedPhone || '')}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            if (data && Array.isArray(data.contacts)) {
                updateContacts(data.contacts);
            }
        } catch (e) {
            console.warn('Unable to refresh contacts list.', e);
        }
    }

    /* ── Auto scroll on load & Scroll-up pagination ── */
    if (body) {
        body.scrollTop = body.scrollHeight;
        body.addEventListener('scroll', () => {
            if (body.scrollTop <= 60 && hasMoreOlder && !isLoadingOlder) {
                fetchOlderMessages();
            }
        });
    }
    initVoiceCards();
    if (selectedPhone) {
        markSelectedChatRead();
        pollMessages();
    } else {
        refreshContactsSidebar();
    }

    // Active conversation polling (every 1.5s for fast delivery ticks & messages)
    setInterval(() => {
        if (selectedPhone) {
            pollMessages();
        } else {
            refreshContactsSidebar();
        }
    }, 1500);

    // Sidebar contacts sync (every 3s to catch new messages from any other number in real-time)
    setInterval(() => {
        if (selectedPhone) {
            refreshContactsSidebar();
        }
    }, 3000);

    // WebSocket Real-time Listening
    if (window.Echo?.private) {
        // 1. Current Active Conversation Channel
        if (selectedPhoneChannel) {
            window.Echo.private(`chat.${selectedPhoneChannel}`)
                .listen('.MessageSent', (e) => {
                    if (e?.message) renderMessage(e.message);
                    pollMessages();
                })
                .listen('.MessageStatusUpdated', (e) => {
                    if (e?.message) updateMessageStatus(e.message);
                });
        }

        // 2. Global WhatsApp Channel for all numbers (Cross-chat live updates)
        window.Echo.private('whatsapp.chat')
            .listen('.MessageSent', (e) => {
                const incomingPhone = e?.message?.phone ? e.message.phone.replace(/\D+/g, '') : '';
                if (selectedPhone && incomingPhone && incomingPhone === selectedPhoneChannel) {
                    if (e?.message) renderMessage(e.message);
                    pollMessages();
                } else {
                    refreshContactsSidebar();
                }
            })
            .listen('.MessageStatusUpdated', (e) => {
                if (e?.message) updateMessageStatus(e.message);
            });
    }

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            if (selectedPhone) {
                markSelectedChatRead();
                pollMessages();
            }
            refreshContactsSidebar();
        }
    });
    window.addEventListener('focus', () => {
        if (selectedPhone) pollMessages();
        refreshContactsSidebar();
    });

})();
</script>

<script>
/* ── Profile Panel (WhatsApp Contact Info Slide-in) ── */
(function () {
    const panel     = document.getElementById('wabProfilePanel');
    const overlay   = document.getElementById('wabProfileOverlay');
    const openBtns  = [
        document.getElementById('wabOpenProfilePanel'),
        document.getElementById('wabOpenProfilePanelText'),
        document.getElementById('wabOpenProfileBtn2'),
    ];
    const closeBtn  = document.getElementById('wabCloseProfilePanel');

    function openPanel() {
        if (!panel) return;
        panel.classList.add('is-open');
        if (overlay) overlay.classList.add('is-visible');
    }

    function closePanel() {
        if (!panel) return;
        panel.classList.remove('is-open');
        if (overlay) overlay.classList.remove('is-visible');
    }

    openBtns.forEach(btn => btn?.addEventListener('click', openPanel));
    closeBtn?.addEventListener('click', closePanel);
    overlay?.addEventListener('click', closePanel);

    /* Close panel on Escape key */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePanel();
    });
})();
</script>

{{-- ══════════════════════════════════════════════════
     IMPORT CONTACTS JAVASCRIPT
══════════════════════════════════════════════════ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
(function () {
    /* ── DOM refs ── */
    const modal         = document.getElementById('waImportContactsModal');
    const dropZone      = document.getElementById('waImportDrop');
    const fileInput     = document.getElementById('waImportFileInput');
    const fileInfo      = document.getElementById('waImportFileInfo');
    const fileNameEl    = document.getElementById('waImportFileName');
    const fileSizeEl    = document.getElementById('waImportFileSize');
    const fileRemoveBtn = document.getElementById('waImportFileRemove');
    const errorEl       = document.getElementById('waImportError');
    const step1         = document.getElementById('waImportStep1');
    const step2         = document.getElementById('waImportStep2');
    const step3         = document.getElementById('waImportStep3');
    const step4         = document.getElementById('waImportStep4');
    const previewBtn    = document.getElementById('waImportPreviewBtn');
    const submitBtn     = document.getElementById('waImportSubmitBtn');
    const doneBtn       = document.getElementById('waImportDoneBtn');
    const backBtn       = document.getElementById('waImportBackBtn');
    const previewBody   = document.getElementById('waImportPreviewBody');
    const rowCountEl    = document.getElementById('waImportRowCount');
    const skippedEl     = document.getElementById('waImportSkipped');
    const skippedText   = document.getElementById('waImportSkippedText');
    const progressBar   = document.getElementById('waImportProgressBar');
    const progressText  = document.getElementById('waImportProgressText');
    const doneTitle     = document.getElementById('waImportDoneTitle');
    const doneSub       = document.getElementById('waImportDoneSub');
    const sampleLink    = document.getElementById('waDownloadSample');

    let selectedFile  = null;
    let parsedRows    = [];
    let validRows     = [];

    const importUrl = @json(route('whatsapp.chat.import-contacts'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ── Download sample CSV ── */
    sampleLink?.addEventListener('click', function (e) {
        e.preventDefault();
        const csv = 'name,phone,country_code\nJohn Smith,9876543210,+44\nSarah Jones,8765432109,+44\nRaj Kumar,7654321098,+91';
        const blob = new Blob([csv], { type: 'text/csv' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'whatsapp_contacts_sample.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    });

    /* ── Drag & Drop ── */
    dropZone?.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone?.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone?.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });
    dropZone?.addEventListener('click', () => fileInput?.click());
    fileInput?.addEventListener('change', e => { if (e.target.files[0]) handleFile(e.target.files[0]); });

    /* ── Remove file ── */
    fileRemoveBtn?.addEventListener('click', resetToStep1);

    /* ── Preview button ── */
    previewBtn?.addEventListener('click', showPreview);

    /* ── Back button ── */
    backBtn?.addEventListener('click', resetToStep1);

    /* ── Submit button ── */
    submitBtn?.addEventListener('click', doImport);

    /* ── Reset on modal close ── */
    modal?.addEventListener('hidden.bs.modal', fullReset);

    /* ── Helpers ── */
    function showError(msg) {
        if (!errorEl) return;
        errorEl.textContent = msg;
        errorEl.style.display = 'block';
    }
    function clearError() {
        if (errorEl) { errorEl.textContent = ''; errorEl.style.display = 'none'; }
    }
    function humanSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
    function normalizePhone(countryCode, phone) {
        const cc = String(countryCode || '').replace(/\D/g, '');
        const ph = String(phone || '').replace(/\D/g, '');
        if (!ph) return null;
        if (ph.startsWith(cc) && cc) return '+' + ph;
        return cc ? '+' + cc + ph : '+' + ph;
    }

    function handleFile(file) {
        clearError();
        const allowedTypes = ['text/csv', 'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        const ext = file.name.split('.').pop().toLowerCase();

        if (!['csv', 'xls', 'xlsx'].includes(ext)) {
            showError('Invalid file type. Please upload a CSV or Excel (.xlsx, .xls) file.');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showError('File is too large. Maximum allowed size is 5 MB.');
            return;
        }

        selectedFile = file;
        if (fileNameEl) fileNameEl.textContent = file.name;
        if (fileSizeEl) fileSizeEl.textContent = '(' + humanSize(file.size) + ')';
        if (fileInfo) fileInfo.style.display = 'flex';
        if (previewBtn) previewBtn.disabled = false;
    }

    function resetToStep1() {
        selectedFile = null;
        parsedRows = [];
        validRows = [];
        if (fileInput) fileInput.value = '';
        if (fileInfo) fileInfo.style.display = 'none';
        if (previewBtn) previewBtn.disabled = true;
        clearError();
        setStep(1);
    }

    function fullReset() {
        resetToStep1();
    }

    function setStep(n) {
        [step1, step2, step3, step4].forEach((s, i) => {
            if (s) s.style.display = (i + 1 === n) ? '' : 'none';
        });
        if (previewBtn)  previewBtn.style.display  = (n === 1) ? '' : 'none';
        if (submitBtn)   submitBtn.style.display   = (n === 2) ? '' : 'none';
        if (doneBtn)     doneBtn.style.display     = (n === 4) ? '' : 'none';
    }

    function parseCSV(text) {
        const lines = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n').filter(l => l.trim());
        if (!lines.length) return [];

        const header = lines[0].split(',').map(h => h.trim().toLowerCase().replace(/[^a-z_]/g, '_'));
        const nameIdx    = header.findIndex(h => h.includes('name'));
        const phoneIdx   = header.findIndex(h => h.includes('phone') || h.includes('mobile') || h.includes('number'));
        const ccIdx      = header.findIndex(h => h.includes('country') || h.includes('code') || h.includes('cc'));

        if (nameIdx < 0 || phoneIdx < 0) return null; // missing required columns

        return lines.slice(1).map((line, i) => {
            const cols = line.match(/(?:^|,)("[^"]*"|[^,]*)/g)?.map(c => c.replace(/^"|"$/g, '').replace(/^,/, '').trim()) || line.split(',').map(c => c.trim());
            return {
                row: i + 2,
                name: cols[nameIdx] || '',
                phone: cols[phoneIdx] || '',
                country_code: ccIdx >= 0 ? (cols[ccIdx] || '') : '',
            };
        });
    }

    function parseXLSX(arrayBuffer) {
        if (typeof XLSX === 'undefined') throw new Error('XLSX library not loaded.');
        const wb = XLSX.read(new Uint8Array(arrayBuffer), { type: 'array' });
        const ws = wb.Sheets[wb.SheetNames[0]];
        const data = XLSX.utils.sheet_to_json(ws, { defval: '' });

        return data.map((row, i) => {
            const keys = Object.keys(row).map(k => k.toLowerCase());
            const get = (terms) => {
                const k = Object.keys(row).find(k => terms.some(t => k.toLowerCase().includes(t)));
                return k ? String(row[k]).trim() : '';
            };
            return {
                row: i + 2,
                name: get(['name']),
                phone: get(['phone', 'mobile', 'number']),
                country_code: get(['country', 'code', 'cc']),
            };
        });
    }

    function showPreview() {
        if (!selectedFile) return;
        clearError();
        previewBtn.disabled = true;
        previewBtn.textContent = 'Parsing…';

        const ext = selectedFile.name.split('.').pop().toLowerCase();
        const reader = new FileReader();

        reader.onload = function (e) {
            try {
                let rows;
                if (ext === 'csv') {
                    rows = parseCSV(e.target.result);
                } else {
                    rows = parseXLSX(e.target.result);
                }

                if (rows === null || rows === undefined) {
                    showError('Could not find required columns. Make sure your file has "name" and "phone" columns.');
                    previewBtn.disabled = false;
                    previewBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Preview';
                    return;
                }

                parsedRows = rows;
                validRows = [];
                const skipped = [];

                if (previewBody) previewBody.innerHTML = '';

                rows.forEach((r, idx) => {
                    const phone = normalizePhone(r.country_code, r.phone);
                    const isValid = !!(r.name.trim() && phone);

                    if (isValid) validRows.push({ name: r.name.trim(), phone });
                    else skipped.push(r.row);

                    if (idx < 100 && previewBody) { // show max 100 rows in preview
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${r.row}</td>
                            <td>${r.name || '<em style="color:#8696a0">—</em>'}</td>
                            <td>${phone || '<em style="color:#8696a0">' + (r.phone || 'missing') + '</em>'}</td>
                            <td class="${isValid ? 'wa-import-row-ok' : 'wa-import-row-warn'}">${isValid ? '✓ Valid' : '✗ Skip'}</td>
                        `;
                        previewBody.appendChild(tr);
                    }
                });

                if (rowCountEl) rowCountEl.textContent = validRows.length;

                if (skipped.length > 0) {
                    if (skippedText) skippedText.textContent = skipped.length + ' row(s) will be skipped (missing name or phone): rows ' + skipped.slice(0, 10).join(', ') + (skipped.length > 10 ? '…' : '.');
                    if (skippedEl) skippedEl.style.display = 'flex';
                } else {
                    if (skippedEl) skippedEl.style.display = 'none';
                }

                if (validRows.length === 0) {
                    showError('No valid contacts found in the file. Please check your data.');
                    previewBtn.disabled = false;
                    previewBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Preview';
                    return;
                }

                setStep(2);

            } catch (err) {
                showError('Failed to parse file: ' + err.message);
                previewBtn.disabled = false;
                previewBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Preview';
            }
        };

        reader.onerror = function () {
            showError('Failed to read the file. Please try again.');
            previewBtn.disabled = false;
        };

        if (ext === 'csv') {
            reader.readAsText(selectedFile);
        } else {
            reader.readAsArrayBuffer(selectedFile);
        }
    }

    async function doImport() {
        if (!validRows.length) return;

        setStep(3);
        if (progressBar) progressBar.style.width = '0%';
        if (progressText) progressText.textContent = 'Importing contacts…';

        const chunkSize = 20;
        const total = validRows.length;
        let imported = 0;
        let failed = 0;

        for (let i = 0; i < total; i += chunkSize) {
            const chunk = validRows.slice(i, i + chunkSize);

            try {
                const response = await fetch(importUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ contacts: chunk }),
                });

                const data = await response.json();
                if (response.ok) {
                    imported += data.imported ?? chunk.length;
                    failed   += data.failed   ?? 0;
                } else {
                    failed += chunk.length;
                }
            } catch (err) {
                failed += chunk.length;
            }

            const progress = Math.round(((i + chunk.length) / total) * 100);
            if (progressBar) progressBar.style.width = Math.min(progress, 100) + '%';
            if (progressText) progressText.textContent = `Importing… ${Math.min(i + chunk.length, total)} / ${total}`;
        }

        /* Done */
        if (doneTitle) doneTitle.textContent = imported > 0 ? 'Import Complete!' : 'Import Finished';
        if (doneSub) {
            doneSub.innerHTML =
                `<strong>${imported}</strong> contact(s) imported successfully.` +
                (failed > 0 ? ` <span style="color:#ef5350">${failed} failed.</span>` : '');
        }
        setStep(4);
    }

    setStep(1);

})();

function openQuickLabelModal(phone, contactName, labelIds) {
    const phoneInput = document.getElementById('waAssignPhoneInput');
    const subtitle = document.getElementById('waAssignContactSubtitle');
    if (phoneInput) phoneInput.value = phone;
    if (subtitle) subtitle.textContent = `for ${contactName || phone} (${phone})`;

    const idSet = new Set((labelIds || []).map(id => parseInt(id)));
    document.querySelectorAll('.wa-contact-label-modal-chk').forEach(chk => {
        chk.checked = idSet.has(parseInt(chk.dataset.labelId || chk.value));
    });

    const modalEl = document.getElementById('waAssignLabelsModal');
    if (modalEl) {
        const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        bsModal.show();
    }
}

function autoApplyWhatsAppModalLabels() {
    const phone = document.getElementById('waAssignPhoneInput')?.value;
    if (!phone) return;

    const checkedBoxes = Array.from(document.querySelectorAll('.wa-contact-label-modal-chk:checked'));
    const labelIds = checkedBoxes.map(chk => parseInt(chk.dataset.labelId || chk.value));
    const labelsData = checkedBoxes.map(chk => ({
        id: parseInt(chk.dataset.labelId || chk.value),
        name: chk.dataset.name,
        color: chk.dataset.color
    }));

    const cleanPhone = phone.replace(/\D+/g, '');

    // 0ms Real-Time Instant Update on Sidebar contact card
    const tagsContainer = document.getElementById(`wab-contact-tags-${cleanPhone}`);
    if (tagsContainer) {
        if (labelsData.length === 0) {
            tagsContainer.innerHTML = '';
        } else {
            const chips = labelsData.slice(0, 4).map(l => `
                <span class="wab-contact-tag-chip" style="background:${l.color}1f;color:${l.color};border:1px solid ${l.color}4d; font-size: 11px; padding: 1.5px 6px; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                    <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:${l.color};"></span>${l.name}
                </span>
            `).join('');
            const extra = labelsData.length > 4 ? `<span class="badge bg-light text-muted border fs-9 px-1.5 py-0.5" style="font-size: 10px;">+${labelsData.length - 4}</span>` : '';
            tagsContainer.innerHTML = chips + extra;
        }
    }

    // Also update quick tag button onclick with updated label IDs
    const contactCard = document.getElementById(`wab-contact-card-${cleanPhone}`);
    if (contactCard) {
        const btn = contactCard.querySelector('.wab-quick-tag-btn');
        if (btn) {
            const cName = contactCard.dataset.name || phone;
            btn.setAttribute('onclick', `event.stopPropagation(); openQuickLabelModal('${phone}', '${cName.replace(/'/g, "\\'")}', ${JSON.stringify(labelIds)})`);
        }
    }

    // Background AJAX Sync
    const assignForm = document.getElementById('waAssignLabelsForm');
    if (!assignForm) return;
    const formData = new FormData(assignForm);

    fetch(assignForm.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    }).catch(err => console.error('Real-time label sync error', err));
}

document.addEventListener('DOMContentLoaded', function() {
    const assignForm = document.getElementById('waAssignLabelsForm');
    if (!assignForm) return;

    assignForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const phone = document.getElementById('waAssignPhoneInput')?.value;
        if (!phone) return;

        const checkedBoxes = Array.from(document.querySelectorAll('.wa-contact-label-modal-chk:checked'));
        const labelIds = checkedBoxes.map(chk => parseInt(chk.dataset.labelId || chk.value));
        const labelsData = checkedBoxes.map(chk => ({
            id: parseInt(chk.dataset.labelId || chk.value),
            name: chk.dataset.name,
            color: chk.dataset.color
        }));

        const cleanPhone = phone.replace(/\D+/g, '');
        const submitBtn = assignForm.querySelector('button[type="submit"]');
        const origBtnText = submitBtn ? submitBtn.innerHTML : 'Save';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving…';
        }

        const formData = new FormData(assignForm);

        fetch(assignForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // Update sidebar contact tags
            const tagsContainer = document.getElementById(`wab-contact-tags-${cleanPhone}`);
            if (tagsContainer) {
                if (labelsData.length === 0) {
                    tagsContainer.innerHTML = '';
                } else {
                    const chips = labelsData.slice(0, 4).map(l => `
                        <span class="wab-contact-tag-chip" style="background:${l.color}1f;color:${l.color};border:1px solid ${l.color}4d; font-size: 11px; padding: 1.5px 6px; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                            <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:${l.color};"></span>${l.name}
                        </span>
                    `).join('');
                    const extra = labelsData.length > 4 ? `<span class="badge bg-light text-muted border fs-9 px-1.5 py-0.5" style="font-size: 10px;">+${labelsData.length - 4}</span>` : '';
                    tagsContainer.innerHTML = chips + extra;
                }
            }

            // Update Quick tag button onclick with new IDs
            const contactCard = document.getElementById(`wab-contact-card-${cleanPhone}`);
            if (contactCard) {
                const btn = contactCard.querySelector('.wab-quick-tag-btn');
                if (btn) {
                    const cName = contactCard.dataset.name || phone;
                    btn.setAttribute('onclick', `event.stopPropagation(); openQuickLabelModal('${phone}', '${cName.replace(/'/g, "\\'")}', ${JSON.stringify(labelIds)})`);
                }
            }

            // Close modal
            const modalEl = document.getElementById('waAssignLabelsModal');
            if (modalEl) {
                const bsModal = bootstrap.Modal.getInstance(modalEl);
                if (bsModal) bsModal.hide();
            }
        })
        .catch(err => {
            console.error('Failed to save labels', err);
            // Fallback submit regular form
            assignForm.submit();
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origBtnText;
            }
        });
    });
});
</script>

@endsection
