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
@endphp

{{-- ======================================================
     WhatsApp Business Chat — Premium UI
     ====================================================== --}}

<div class="wab-page" id="wabPage">

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
                <button class="wab-icon-btn" data-bs-toggle="modal" data-bs-target="#newWaChatModal" title="New Chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="11"/><line x1="8" y1="11" x2="8" y2="11"/><line x1="16" y1="11" x2="16" y2="11"/></svg>
                </button>
                <button class="wab-icon-btn" id="wabToggleSidebar" title="Collapse">
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

        @if(!empty($enabledPanelKeys))
            <div class="wab-panel-tabs">
                @foreach($enabledPanelKeys as $panelKey)
                    @php $panel = $panelDefinitions[$panelKey] ?? null; @endphp
                    @if($panel)
                        <a class="wab-panel-chip {{ $selectedPanel === $panelKey ? 'is-active' : '' }}" href="{{ route('whatsapp.chat', array_filter(['phone' => $selectedPhone, 'panel' => $panelKey])) }}" title="{{ $panel['label'] }}">
                            {{ $panel['short'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

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
            <div class="wab-contact-item {{ $c['active'] ? 'is-active' : '' }}" data-name="{{ strtolower($c['name']) }}" data-contact-id="{{ $c['id'] }}" data-url="{{ isset($c['phone']) ? route('whatsapp.chat', ['phone' => $c['phone']]) : '' }}" data-phone="{{ $cPhone }}" data-color="{{ $c['color'] }}">
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
                        <div class="wab-contact-row-right">
                            @if($c['badge'])
                                <span class="wab-badge">{{ $c['badge'] }}</span>
                            @endif
                            @if($cLabels->isNotEmpty())
                                <div class="wab-contact-label-dots">
                                    @foreach($cLabels->take(3) as $lbl)
                                        <span class="wab-label-dot" style="background:{{ $lbl->color }}" title="{{ $lbl->name }}"></span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($cLabels->isNotEmpty())
                        <div class="wab-contact-label-tags">
                            @foreach($cLabels->take(2) as $lbl)
                                <span class="wab-contact-tag-chip" style="background:{{ $lbl->color }}1a;color:{{ $lbl->color }};border:1px solid {{ $lbl->color }}40">{{ $lbl->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </aside>

    {{-- ── CONVERSATION PANEL ── --}}
    <section class="wab-conversation">

        {{-- Conv Header --}}
        <div class="wab-conv-header">
            <div class="wab-conv-header-left">
                <button class="wab-icon-btn wab-mobile-back" id="wabMobileBack">
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
            <div class="wab-conv-actions">
                @if($selectedPhone)
                    <button class="wab-label-btn" data-bs-toggle="modal" data-bs-target="#waAssignLabelsModal" title="Label chat">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Label chat
                    </button>
                @endif
                <button class="wab-icon-btn" title="Search in chat">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
                <button class="wab-icon-btn" title="Voice call">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.72 12 19.79 19.79 0 0 1 1.65 3.4 2 2 0 0 1 3.62 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.81a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </button>
                <button class="wab-icon-btn" title="Video call">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                </button>
                <button class="wab-icon-btn" id="wabOpenProfileBtn2" title="Contact Info">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </button>
            </div>
        </div>

        {{-- Messages Body --}}
        <div class="wab-messages-body" id="wabMessagesBody">

            <div class="wab-date-badge">Today</div>

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

            @forelse($messages->filter(fn($message) => trim($message->message ?? '') !== '') as $message)
                <div class="wab-msg-row {{ $message->direction === 'inbound' ? 'wab-incoming' : 'wab-outgoing' }}">
                    <div class="wab-msg-bubble">
                        {{ $message->message }}
                        <div class="wab-msg-meta">
                            <span class="wab-msg-time">{{ optional($message->created_at)->format('H:i') }}</span>
                            @if($message->direction === 'outbound')
                                <span class="wab-tick wab-tick--{{ $message->status === 'read' ? 'read' : 'sent' }}">
                                    <svg width="14" height="10" viewBox="0 0 18 12" fill="none"><path d="M1 6l4 4L17 1" stroke="{{ $message->status === 'read' ? '#53bdeb' : '#8696a0' }}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="wab-msg-row wab-incoming">
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
            <div class="wab-msg-row wab-incoming wab-typing-row" id="wabTypingRow">
                <div class="wab-msg-bubble wab-typing-bubble">
                    <span class="wab-typing-dot"></span>
                    <span class="wab-typing-dot"></span>
                    <span class="wab-typing-dot"></span>
                </div>
            </div>

        </div>

        {{-- Conv Footer --}}
        <form class="wab-conv-footer" method="POST" action="{{ route('whatsapp.chat.send') }}">
            @csrf
            <input type="hidden" name="phone" value="{{ $selectedPhone }}">
            <div class="wab-footer-actions-left">
                <button class="wab-icon-btn wab-emoji-btn" id="wabEmojiBtn" title="Emoji">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </button>
                <button class="wab-icon-btn" title="Attach file">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                </button>
            </div>
            <div class="wab-input-wrap">
                <input type="text" class="wab-input" id="wabInput" name="message" placeholder="Type a message…" autocomplete="off" {{ $selectedPhone ? '' : 'disabled' }}>
            </div>
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
    --sidebar-w:      340px;
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
    grid-template-columns: 72px 1fr;
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

.sidebar-collapsed .wab-sidebar-title,
.sidebar-collapsed .wab-search-wrap,
.sidebar-collapsed .wab-tabs,
.sidebar-collapsed .wab-contact-info,
.sidebar-collapsed .wab-sidebar-header-left > :not(.wab-avatar) { display: none; }

.sidebar-collapsed .wab-sidebar-header {
    justify-content: center;
    padding: 0 8px;
}
.sidebar-collapsed .wab-header-actions { flex-direction: column; gap: 6px; }
.sidebar-collapsed .wab-contact-item { justify-content: center; padding: 10px 0; }
.sidebar-collapsed .wab-avatar--header { display: none; }

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
.wab-tab.active, .wab-tab:hover {
    background: rgba(37,211,102,.12);
    color: var(--wa-green-dark);
    font-weight: 600;
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
.wab-contact-name    { font-size: 14px; font-weight: 600; color: var(--wa-text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px; }
.wab-contact-time    { font-size: 11px; color: var(--wa-text-sub); white-space: nowrap; }
.wab-contact-preview { font-size: 12.5px; color: var(--wa-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px; }
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

/* ── Typing indicator ── */
.wab-typing-row { margin-bottom: 10px; }
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
.wab-footer-actions-left { display: flex; align-items: center; gap: 2px; }
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
</style>

{{-- ══════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════ --}}
<script>
(function () {
    const page          = document.getElementById('wabPage');
    const sidebar       = document.getElementById('wabSidebar');
    const toggleBtn     = document.getElementById('wabToggleSidebar');
    const emojiBtn      = document.getElementById('wabEmojiBtn');
    const emojiPanel    = document.getElementById('wabEmojiPanel');
    const sendBtn       = document.getElementById('wabSendBtn');
    const input         = document.getElementById('wabInput');
    const sendForm      = document.querySelector('.wab-conv-footer');
    const body          = document.getElementById('wabMessagesBody');
    const searchInput   = document.getElementById('wabSearchInput');
    const mobileBack    = document.getElementById('wabMobileBack');
    const typingRow     = document.getElementById('wabTypingRow');
    const typingLabel   = document.getElementById('wabTypingLabel');

    /* ── Toggle sidebar collapse (desktop) ── */
    toggleBtn?.addEventListener('click', () => {
        page.classList.toggle('sidebar-collapsed');
    });

    /* ── Mobile back ── */
    mobileBack?.addEventListener('click', () => {
        sidebar.classList.remove('mobile-hidden');
    });

    /* ── Contact click ── */
    document.querySelectorAll('.wab-contact-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.wab-contact-item').forEach(i => i.classList.remove('is-active'));
            item.classList.add('is-active');
            if (window.innerWidth <= 768) sidebar.classList.add('mobile-hidden');
            if (item.dataset.url) window.location.href = item.dataset.url;
        });
    });

    /* ── Tab filter ── */
    document.querySelectorAll('.wab-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.wab-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    /* ── Search filter ── */
    searchInput?.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.wab-contact-item').forEach(item => {
            item.style.display = item.dataset.name.includes(q) ? '' : 'none';
        });
    });

    /* ── Emoji panel ── */
    emojiBtn?.addEventListener('click', (e) => {
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
    function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        const row    = document.createElement('div');
        const bubble = document.createElement('div');
        const meta   = document.createElement('div');
        const time   = document.createElement('span');
        const tick   = document.createElement('span');

        row.className    = 'wab-msg-row wab-outgoing';
        bubble.className = 'wab-msg-bubble';
        meta.className   = 'wab-msg-meta';
        time.className   = 'wab-msg-time';
        tick.className   = 'wab-tick wab-tick--sent';

        const now = new Date();
        time.textContent = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
        tick.innerHTML = `<svg width="14" height="10" viewBox="0 0 18 12" fill="none"><path d="M1 6l4 4L17 1" stroke="#8696a0" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;

        bubble.textContent = text;
        meta.append(time, tick);
        bubble.appendChild(meta);
        row.appendChild(bubble);

        /* insert before typing row */
        body.insertBefore(row, typingRow);
        body.scrollTop = body.scrollHeight;
        input.value = '';

        /* simulate typing indicator */
        typingRow.style.display = 'flex';
        typingLabel.textContent  = 'typing…';
        setTimeout(() => {
            typingRow.style.display = 'none';
            typingLabel.textContent  = 'online';
        }, 2800);
    }

    sendBtn?.addEventListener('click', e => { if (!input?.value.trim()) e.preventDefault(); });
    input?.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendForm?.requestSubmit(); } });

    /* ── Auto scroll on load ── */
    if (body) body.scrollTop = body.scrollHeight;

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

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePanel();
    });
})();
</script>

<script>
/* ════════════════════════════════════════
   WHATSAPP-STYLE LABEL TOGGLE & AJAX
════════════════════════════════════════ */

/* ── 1. Toggle label row selection (tap) ── */
function toggleLabel(el) {
    el.classList.toggle('is-selected');
    const check = el.querySelector('.wab-wa-check');
    const color = el.dataset.labelColor;
    if (el.classList.contains('is-selected')) {
        check.style.borderColor = color;
        check.style.background = color + '20';
    } else {
        check.style.borderColor = '#ccc';
        check.style.background = 'transparent';
    }
}

/* ── 2. Label search in assign modal ── */
const labelSearchInput = document.getElementById('labelSearchInput');
labelSearchInput?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.wab-wa-label-row').forEach(row => {
        const name = (row.dataset.labelName || '').toLowerCase();
        row.style.display = name.includes(q) ? '' : 'none';
    });
});

/* ── 3. Save assigned labels via AJAX ── */
const saveLabelAssignBtn = document.getElementById('saveLabelAssignBtn');
saveLabelAssignBtn?.addEventListener('click', async () => {
    const selected = [];
    document.querySelectorAll('.wab-wa-label-row.is-selected').forEach(row => {
        selected.push(row.dataset.labelId);
    });

    const phone = '{{ $selectedPhone }}';
    if (!phone) { alert('No contact selected.'); return; }

    const btn = saveLabelAssignBtn;
    btn.disabled = true;
    btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Saving…`;

    try {
        const response = await fetch('{{ route('whatsapp.chat.contact-labels.save') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ phone, label_ids: selected }),
        });

        if (response.ok) {
            /* Close modal */
            const modal = bootstrap.Modal.getInstance(document.getElementById('waAssignLabelsModal'));
            modal?.hide();
            /* Reload page to reflect new label dots in sidebar */
            setTimeout(() => window.location.reload(), 300);
        } else {
            const data = await response.json().catch(() => ({}));
            alert(data.message || 'Failed to save labels.');
            btn.disabled = false;
            btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Save Labels`;
        }
    } catch (err) {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Save Labels`;
    }
});

/* ── 4. Color preview dot in Settings modal ── */
const newLabelColor = document.getElementById('newLabelColor');
const labelColorPreview = document.getElementById('labelColorPreview');
newLabelColor?.addEventListener('input', () => {
    if (labelColorPreview) labelColorPreview.style.background = newLabelColor.value;
});

/* ── 5. AJAX label creation in Settings modal ── */
const createLabelBtn = document.getElementById('createLabelBtn');
createLabelBtn?.addEventListener('click', async () => {
    const nameInput = document.getElementById('newLabelName');
    const name = nameInput?.value.trim();
    const color = newLabelColor?.value || '#00a884';
    const msgEl = document.getElementById('createLabelMsg');

    if (!name) {
        nameInput?.focus();
        if (msgEl) { msgEl.style.display = 'block'; msgEl.style.color = '#ef5350'; msgEl.textContent = 'Please enter a label name.'; }
        return;
    }

    createLabelBtn.disabled = true;
    createLabelBtn.textContent = 'Adding…';

    try {
        const response = await fetch('{{ route('whatsapp.chat.labels.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ name, color }),
        });

        const data = await response.json().catch(() => ({}));

        if (response.ok && data.label) {
            const label = data.label;

            /* Add to existing labels grid (Settings modal) */
            const grid = document.getElementById('existingLabelsGrid');
            const hint = document.getElementById('noLabelsHint');
            if (hint) hint.remove();
            if (grid) {
                const pill = document.createElement('div');
                pill.className = 'wab-exist-label-pill';
                pill.dataset.id = label.id;
                pill.innerHTML = `<span class="wab-exist-dot" style="background:${label.color}"></span><span class="wab-exist-name">${label.name}</span>`;
                grid.appendChild(pill);
            }

            /* Add to Label Chat modal list */
            const waList = document.getElementById('waLabelList');
            const noTxt  = document.getElementById('noLabelsTxt');
            if (noTxt) noTxt.remove();
            if (waList) {
                const row = document.createElement('div');
                row.className = 'wab-wa-label-row';
                row.dataset.labelId = label.id;
                row.dataset.labelName = label.name;
                row.dataset.labelColor = label.color;
                row.setAttribute('onclick', 'toggleLabel(this)');
                row.innerHTML = `
                    <div class="wab-wa-label-left">
                        <span class="wab-wa-label-icon" style="background:${label.color}">${label.name[0].toUpperCase()}</span>
                        <span class="wab-wa-label-name">${label.name}</span>
                    </div>
                    <span class="wab-wa-check" style="border-color:${label.color}">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="${label.color}" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>`;
                waList.appendChild(row);
            }

            /* Reset form */
            if (nameInput) nameInput.value = '';
            if (msgEl) { msgEl.style.display = 'block'; msgEl.style.color = '#128c7e'; msgEl.textContent = `✓ Label "${label.name}" created!`; setTimeout(() => { msgEl.style.display='none'; }, 3000); }

        } else {
            if (msgEl) { msgEl.style.display = 'block'; msgEl.style.color = '#ef5350'; msgEl.textContent = data.message || 'Failed to create label.'; }
        }
    } catch (err) {
        if (msgEl) { msgEl.style.display = 'block'; msgEl.style.color = '#ef5350'; msgEl.textContent = 'Network error. Try again.'; }
    }

    createLabelBtn.disabled = false;
    createLabelBtn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add`;
});

/* spinner keyframe */
const spinStyle = document.createElement('style');
spinStyle.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(spinStyle);
</script>

@endsection
