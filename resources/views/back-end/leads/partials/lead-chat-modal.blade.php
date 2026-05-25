<div id="chatBox" class="chat-box p-3 bg-white rounded shadow-sm">
    @foreach($lead->call as $chat)
        @php
            $isCurrentUser = $chat->created_by == Auth::user()->id;
        @endphp

        <div class="d-flex mb-2 {{ $isCurrentUser ? 'justify-content-end' : 'justify-content-start' }}">
            <div class="message-bubble {{ $isCurrentUser ? 'sent' : 'received' }}">
                <div class="message-text">
                    {{ $chat->description }}
                </div>
                <div class="message-meta">
                    @if($isCurrentUser)
                        You • {{ $chat->created_at->format('d-m-Y h:i A') }}
                    @else
                    {{ $chat->user->name }} • {{ $chat->created_at->format('d-m-Y h:i A') }}
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
.chat-box {
    height: 100%;
    max-height: 100%;
    overflow-y: auto;
    background-color: #e5ddd5;
    border-radius: 10px;
    display: flex;
    flex-direction: column-reverse;
    padding: 1rem;
    font-family: 'Segoe UI', sans-serif;
}

.message-bubble {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 18px;
    box-shadow: 0 2px 3px rgba(0,0,0,0.1);
    position: relative;
    font-size: 0.95rem;
    line-height: 1.4;
}

.message-bubble.sent {
    background-color: #dcf8c6;
    color: #111;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}

.message-bubble.received {
    background-color: #ffffff;
    color: #111;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
}

.message-meta {
    font-size: 0.7rem;
    color: #666;
    margin-top: 5px;
    text-align: right;
}

.message-text {
    word-wrap: break-word;
}
</style>

