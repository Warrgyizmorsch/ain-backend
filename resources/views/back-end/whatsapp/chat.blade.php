@extends('layouts.app')

@section('content')

{{-- ======================================================
     WhatsApp Business Chat — Premium UI
     ====================================================== --}}

<div class="wab-page" id="wabPage">

    {{-- ── CONTACT SIDEBAR ── --}}
    <aside class="wab-sidebar" id="wabSidebar">

        {{-- Header --}}
        <div class="wab-sidebar-header">
            <div class="wab-sidebar-header-left">
                <div class="wab-avatar wab-avatar--header">A</div>
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

        <div class="wab-contact-list" id="wabContactList">
            @foreach($contacts as $c)
            <div class="wab-contact-item {{ $c['active'] ? 'is-active' : '' }}" data-name="{{ strtolower($c['name']) }}" data-contact-id="{{ $c['id'] }}">
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
                        @if($c['badge'])
                            <span class="wab-badge">{{ $c['badge'] }}</span>
                        @endif
                    </div>
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
                <div class="wab-avatar wab-avatar--conv" style="background:#25d3661a;color:#25d366">A</div>
                <div class="wab-conv-user-info">
                    <div class="wab-conv-name">Aarav Sharma</div>
                    <div class="wab-conv-status">
                        <span class="wab-online-dot"></span>
                        <span id="wabTypingLabel">online</span>
                    </div>
                </div>
            </div>
            <div class="wab-conv-actions">
                <button class="wab-icon-btn" title="Search in chat">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
                <button class="wab-icon-btn" title="Voice call">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.72 12 19.79 19.79 0 0 1 1.65 3.4 2 2 0 0 1 3.62 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.81a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </button>
                <button class="wab-icon-btn" title="Video call">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                </button>
                <button class="wab-icon-btn" title="More options">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
            </div>
        </div>

        {{-- Messages Body --}}
        <div class="wab-messages-body" id="wabMessagesBody">

            <div class="wab-date-badge">Today</div>

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
        <div class="wab-conv-footer">
            <div class="wab-footer-actions-left">
                <button class="wab-icon-btn wab-emoji-btn" id="wabEmojiBtn" title="Emoji">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </button>
                <button class="wab-icon-btn" title="Attach file">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                </button>
            </div>
            <div class="wab-input-wrap">
                <input type="text" class="wab-input" id="wabInput" placeholder="Type a message…" autocomplete="off">
            </div>
            <button class="wab-send-btn" id="wabSendBtn" title="Send">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>

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
                    <select class="wab-form-select" style="flex:0 0 110px">
                        <option value="+91">🇮🇳 +91</option>
                        <option value="+44">🇬🇧 +44</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+61">🇦🇺 +61</option>
                        <option value="+971">🇦🇪 +971</option>
                    </select>
                    <input type="tel" class="wab-form-input" placeholder="Mobile number">
                </div>
                <label class="wab-form-label mt-3">First Message</label>
                <textarea class="wab-form-input" rows="3" placeholder="Hi, I wanted to reach out about…" style="resize:none;padding-top:10px"></textarea>
                <p class="wab-form-note">This will open a new conversation thread via your WhatsApp Business API.</p>
            </div>
            <div class="modal-footer wab-modal-footer">
                <button type="button" class="wab-btn wab-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="wab-btn wab-btn--primary" data-bs-dismiss="modal">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Open Chat
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════ --}}
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

    sendBtn?.addEventListener('click', sendMessage);
    input?.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

    /* ── Auto scroll on load ── */
    if (body) body.scrollTop = body.scrollHeight;

})();
</script>

@endsection
