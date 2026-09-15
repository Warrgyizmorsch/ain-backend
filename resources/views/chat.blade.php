<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Chat</title>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }
        .chat-container {
            display: flex;
            height: 100vh;
        }

        /* Left Panel (Contacts) */
        .contacts {
            width: 25%;
            background: #fff;
            border-right: 1px solid #ccc;
            overflow-y: auto;
        }

        .contacts a {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .contacts a.active {
            background: #3fb7b3;
            color: #fff;
        }

        /* Right Panel (Chat + Form) */
        .chat-panel {
            width: 75%;
            padding: 20px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chat-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .message {
            position: relative;
            max-width: 70%;
            padding: 10px 32px 10px 12px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.4;
        }

        .copy-msg-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 4px;
            width: 22px;
            height: 22px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.15s, background 0.15s;
            color: #555;
        }
        .message:hover .copy-msg-btn {
            opacity: 1;
        }
        .copy-msg-btn.copied {
            opacity: 1 !important;
            background: #e8f5e9 !important;
            color: #16a34a !important;
        }

        .incoming {
            align-self: flex-start;
            background-color: #3fb7b324;
        }

        .outgoing {
            align-self: flex-end;
            background-color: #a9c0c024;
        }

        form {
            display: flex;
            gap: 10px;
        }

        textarea {
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border-radius: 6px;
        }

        button {
            padding: 10px 15px;
            background: #3fb7b3;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .success, .error {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="chat-container">

    {{-- Left Panel: Contact List --}}
    <div class="contacts">
        @foreach($contacts as $c)
            <a href="{{ route('chat', $c->phone) }}" class="{{ $phone == $c->phone ? 'active' : '' }}">
                {{ $c->phone }}
            </a>
        @endforeach
    </div>

    {{-- Right Panel: Chat + Form --}}
    <div class="chat-panel">
        @if($phone)
            <div class="chat-header">Chat with {{ $phone }}</div>

            <div class="chat-box">
                @foreach($messages as $msg)
                    <div class="message {{ $msg->direction === 'inbound' ? 'incoming' : 'outgoing' }}">
                        <button type="button" class="copy-msg-btn" onclick="copySimpleMsg(this, {{ json_encode($msg->message) }})" title="Copy message">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                        </button>
                        {{ $msg->message }}
                        <div style="font-size: 10px; color: #666; margin-top: 4px;">
                            {{ $msg->created_at->format('d M, h:i A') }}
                        </div>
                    </div>
                @endforeach
            </div>
            <form x-data="chatForm()" @submit.prevent="send">
                <input type="hidden" name="phone" :value="phone">
                <textarea x-model="message" required></textarea>
                <button type="submit">Send</button>
            </form>

            {{-- <form action="{{ route('send-whatsapp') }}" method="POST">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">
                <textarea name="message" placeholder="Type your message..." required></textarea>
                <button type="submit">Send</button>
            </form> --}}

            @if(session('status'))
                <div class="success">{{ session('status') }}</div>
            @elseif(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif
        @else
            <div class="chat-header">Select a contact to start chatting</div>
        @endif
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
<script>
    function chatForm() {
        return {
            phone: '{{ $phone }}',
            message: '',
            async send() {
                const response = await fetch('{{ route("send-whatsapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ phone: this.phone, message: this.message })
                });

                if (response.ok) {
                    this.message = '';
                } else {
                    alert('Error sending message.');
                }
            }
        }
    }
</script>

<script>
    window.addEventListener('load', () => {
        const chatBox = document.querySelector('.chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>

<script>
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("PUSHER_APP_KEY") }}',
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        forceTLS: true,
        encrypted: true,
    });

// console.log('Echo initialized');

// Hardcode to test
// window.Echo.private('chat.{{ $phone }}')

window.Echo.private('chat.{{ $phone }}')
    .listen('.MessageSent', (e) => {
        // console.log('📬 Message received via Echo:', e);

        const chatBox = document.querySelector('.chat-box');
        const msgDiv = document.createElement('div');
        msgDiv.classList.add('message', e.message.direction === 'inbound' ? 'incoming' : 'outgoing');
        msgDiv.innerHTML = `
            <button type="button" class="copy-msg-btn" onclick="copySimpleMsg(this, ${JSON.stringify(e.message.message)})" title="Copy message">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </button>
            ${e.message.message}
            <div style="font-size: 10px; color: #666; margin-top: 4px;"> ${dayjs().format('D MMM, h:mm A')}</div>
        `;
        chatBox.appendChild(msgDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>

<script>
    function copySimpleMsg(btn, text) {
        if (!text) return;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
        btn.classList.add('copied');
        btn.setAttribute('title', 'Copied!');
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.setAttribute('title', 'Copy message');
        }, 1500);
    }
</script>

</body>
</html>
