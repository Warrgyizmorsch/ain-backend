<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@extends('layouts.app')
<style type="text/css">
    .tab-content {
    display: none;
}
#tab-all:checked ~ .tabs-content #contact-all,
#tab-read:checked ~ .tabs-content #contact-read,
#tab-unread:checked ~ .tabs-content #contact-unread,
#tab-active:checked ~ .tabs-content #contact-active {
    display: block;
}
.file-preview {
    display: flex;
    /* flex-direction: column; */
    align-items: flex-start;
    position: relative;
    gap: 8px;
    width: 70%;
    /* max-width: 600px;
    height: 350px; */
    padding: 10px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.file-preview img,
.file-preview video {
    width: 70%;
    /* max-height: 300px; */
    object-fit: contain;
    border-radius: 6px;
}

.file-preview .remove-btn {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    width: 24px;
    height: 24px;
    font-size: 16px;
    line-height: 1;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.file-preview input[type="text"] {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

.file-preview button.send-btn {
    margin-top: 6px;
    width: 100%;
    background-color: #16a34a;
    color: white;
    padding: 8px;
    border:0;
    border-radius: 6px;
    text-align: center;
    font-weight: 500;
    transition: background-color 0.2s ease;
}

.file-preview button.send-btn:hover {
    background-color: #15803d;
}



</style>
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div class="app-container">
            <!-- Sidebar -->
            <div class="left-sidebar">
                <div class="sidebar" id="sidebar">
                    <!-- Header -->
                    <div class="sidebar-header">
                        <div class="user-avatar">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                                alt="User">
                        </div>
                        @if(Auth::check())
                            <span>{{ Auth::user()->name }}</span>
                        @else
                            <span>Guest</span>
                        @endif

                    </div>

                    <!-- Search -->
                    <div class="search-container">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search or start new chat">
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="tabs-wrapper">
                        <!-- Radio Inputs -->
                        <input type="radio" name="tabs" class="tab-input" id="tab-all" checked>
                        <input type="radio" name="tabs" class="tab-input" id="tab-read">
                        <input type="radio" name="tabs" class="tab-input" id="tab-unread">
                        {{-- <input type="radio" name="tabs" class="tab-input" id="tab-active"> --}}


                        <!-- Tab Labels -->
                        <div class="tab-labels">
                            <label for="tab-all" class="tab-label">All</label>
                            <label for="tab-read" class="tab-label">Read</label>
                            <label for="tab-unread" class="tab-label">Unread</label>
                            {{-- <label for="tab-active" class="tab-label">Active</label> --}}
                        </div>

                        <!-- Tab Contents -->
                        <div class="tabs-content">
                           @foreach (['all', 'read', 'unread'] as $type)
                                <div class="chats-bar tab-content" id="contact-{{ $type }}">
                                    @forelse ($contacts[$type] as $c)
                                        <a href="{{ route('chat', $c['phone']) }}" class="chat-links {{ $phone == $c['phone'] ? 'active' : '' }}" data-phone="{{ $c['phone'] }}">
                                        <div class="chat-item">
                                            <img src="{{ $c['avatar'] }}" alt="{{ $c['name'] }}" />
                                            
                                            <div class="chat-list-info">
                                                <div class="chat-list-header">
                                                    <h4>{{ $c['name'] }}</h4>
                                                    <div class="chat-meta">
                                                        <span class="message-time">
                                                            @if ($c['last_seen'])
                                                                {{ \Carbon\Carbon::parse($c['last_seen'])->format('H:i') }}
                                                            @endif
                                                        </span>
                                                        @if ($c['unread_count'] > 0)
                                                            <span class="unread-bubble">{{ $c['unread_count'] }}</span>
                                                        @elseif ($c['is_unread'])
                                                            <span class="unread-bubble empty"></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="message-info">
                                                    <p>{{ $c['last_message'] ?? 'No messages yet' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>


                                    @empty
                                        <p class="empty-list">No {{ ucfirst($type) }} chats</p>
                                    @endforelse
                                </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- Left Panel: Contact List --}}
                    {{-- <div class="chats-bar">
                      @foreach ($contacts as $c)
                      <a href="{{ route('chat', $c['phone']) }}" class="{{ $phone == $c['phone'] ? 'active' : '' }}">
                       <div class="chat-item">
                            <img src="{{ $c['avatar'] }}" alt="{{ $c['name'] }}" />
                                <div class="chat-list-info">
                                    <div class="chat-list-header">
                                        <h4>{{ $c['name'] }}</h4>
                                        <span class="message-time">
                                            @if ($c['last_seen'])
                                                {{ \Carbon\Carbon::parse($c['last_seen'])->diffForHumans() }}
                                            @endif
                                        </span>
                                    </div>

                                    <div class="message-info">
                                        <p>{{ $c['last_message'] ?? 'No messages yet' }}</p>

                                        @php
                                            $unreadCount = $c['unread_count'] ?? 0;
                                            $isUnread = $c['is_unread'] ?? false;
                                        @endphp

                                        @if ($unreadCount > 0)
                                            <div class="unread-bubble">{{ $unreadCount }}</div>
                                        @elseif ($isUnread)
                                            <div class="unread-bubble empty"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div> --}}
                </div>
            </div>
                <div class="chat-area">
                    <div class="chat-header">
                        <!-- Hamburger Icon -->
                        <div class="chat-profile">
                            <button id="toggleSidebarBtn" class="hamburger-btn" title="Toggle Sidebar">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="toggle-sidebar-btn">
                                <div class="chat-info">
                                    <a id="open-profile-btn" href="#" class="chat-link">
                                        <div class="chat-avatar">
                                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                                                alt="Contact">
                                        </div>
                                        <div class="chat-details">
                                            <h3>{{ $chatUser['name'] }}</h3>
                                            <p>{{ $chatUser['time'] }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="chat-actions">
                            <!-- Call button -->
                            <button class="call-btn" title="Call">
                                <i class="fas fa-phone"></i>
                            </button>
                            <!-- Dropdown -->
                            <div class="dropdown">
                                <button class="dropdown-btn" onclick="toggleDropdown(event)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-content" id="dropdownMenu">
                                    <button>Close chat</button>
                                    <button>Mark as unread</button>
                                    <hr>
                                    <button>Contact info</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Panel: Chat + Form --}}
                    <div class="chat-body">
                         <div class="chat-box">
                           @foreach($chatUser['chats'] as $msg)
                                @php
                                    $status = $msg['status'];
                                    $isOutbound = $msg['direction'] === 'outbound';
                                @endphp
                                <div class="chat-message {{ $isOutbound ? 'me' : 'user' }}" data-wa-id="{{ $msg['id'] }}">
                                    <div class="message-bubble {{ $isOutbound ? 'me' : 'user' }}">
                                        <p>{{ $msg['message'] }}</p>
                                        <div class="message-meta">
                                            <small class="message-time">{{ $msg['created_at'] }}</small>
                                            @if ($isOutbound)
                                                <i class="{{ $status === 'read' ? 'fa-solid fa-check-double text-read' : ($status === 'delivered' ? 'fa-solid fa-check-double text-sent' : ($status === 'sent' ? 'fas fa-check text-sent' : '')) }} status-icon"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                   
                    <form x-data="chatForm()" @submit.prevent="send" enctype="multipart/form-data">
                        <input type="hidden" name="phone" x-model="phone" value="{{ $phone }}">
                       <!-- Media Preview -->
                       <div x-show="file" class="relative mt-4 p-3 border rounded bg-gray-50 file-preview w-64">
                            <!-- Remove Button -->
                            <button @click="removePreview" class="absolute top-1 left-1 bg-black bg-opacity-50 text-white rounded-full w-6 h-6 flex items-center justify-center">
                                &times;
                            </button>

                            <!-- Image Preview -->
                            <template x-if="fileType === 'image'">
                                <img :src="fileURL" class="rounded mb-2 w-full max-h-48 object-cover" />
                            </template>

                            <!-- Video Preview -->
                            <template x-if="fileType === 'video'">
                                <video :src="fileURL" class="rounded mb-2 w-full max-h-48" controls></video>
                            </template>

                            <!-- Document Preview -->
                            <template x-if="fileType === 'document'">
                                <div class="text-blue-600 p-2 flex items-center gap-2">
                                    <i class="fa fa-file fa-2x"></i>
                                    <span x-text="file?.name"></span>
                                </div>
                            </template>

                            <!-- Audio Preview -->
                            <template x-if="fileType === 'audio'">
                                <audio :src="fileURL" controls class="w-full mb-2"></audio>
                            </template>

                            <!-- Caption + Send button -->
                            <div class="mt-2">
                                <input type="text" x-model="caption" class="border px-2 py-1 w-full rounded mb-2" placeholder="Enter caption (optional)" />
                                <button @click.prevent="sendMedia"
                                        type="button"
                                        class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700 w-full send-btn"
                                        :disabled="isSending"
                                        :class="{ 'opacity-50 cursor-not-allowed': isSending }">
                                    Send
                                </button>
                            </div>
                        </div>


                        <div class="message-input-container">
                            <div class="input-actions" style="position: relative;">
                                <button type="button" class="emoji-btn">
                                    <i class="fa-solid fa-face-smile fa-sm"></i>
                                </button>

                                <!-- Attachment Dropdown -->
                                <div class="dropup-wrapper">
                                    <button type="button" class="dropup-btn"><i class="fa-solid fa-paperclip fa-sm"></i></button>
                                    <div class="dropup-menu hidden">
                                       <!-- Image -->
                                        <label style="display: block; cursor: pointer; color: #fff;">
                                            <i class="fa fa-image fa-fw icon-blue"></i> Image
                                            <input type="file" accept="image/*" class="hidden" @change="handleFileChange($event)">
                                        </label>

                                        <!-- Video -->
                                        <label style="display: block; cursor: pointer; color: #fff;">
                                            <i class="fa fa-video fa-fw icon-blue"></i> Video
                                            <input type="file" accept="video/*" class="hidden" @change="handleFileChange($event)">
                                        </label>

                                        <!-- Document -->
                                        <label style="display: block; cursor: pointer; color: #fff;">
                                            <i class="fa fa-file-pdf fa-fw icon-blue"></i> Document
                                            <input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.*,text/plain" class="hidden" @change="handleFileChange($event)">
                                        </label>
                                        <!-- Audio -->
                                        <label style="display: block; cursor: pointer; color: #fff;">
                                            <i class="fa fa-music fa-fw icon-blue"></i> Audio
                                            <input type="file" accept="audio/*" class="hidden" @change="handleFileChange($event)">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Message input -->
                            <div class="message-input">
                                <textarea x-model="message"
                                        @keydown.enter.prevent="!$event.shiftKey && send()"
                                        placeholder="Type a message">
                                </textarea>
                            </div>

                            <!-- Send button -->
                            <div class="send-btn">
                                <button type="submit">
                                    <i class="fa-solid fa-paper-plane fa-sm" style="color: #4CAF50;"></i>
                                </button>
                            </div>
                        </div>

                        
                    </form>

                <!-- Profile Details -->
                <div class="profile-details" id="profile-details">
                    <div class="profile-details-btn">
                        <button class="profile-details-close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                        <h1>Chat Profile</h1>
                    </div>
                    <div class="profile-items">
                        <div class="chat-container">
                            <div class="chat-avatar">
                                <img id="profile-details-avatar"
                                    src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                                    alt="">
                            </div>
                            <div class="chat-details">
                                <h3 id="profile-details-name">{{ $chatUser['name'] }}</h3>
                                <p id="profile-details-status">{{ $chatUser['time'] }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="info-table-wrapper">
                            <table class="info-table">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chatUser['extra'] as $key => $value)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>
                                                @if ($key === 'Opted In')
                                                    <label class="switch">
                                                        <input type="checkbox" disabled {{ $value ? 'checked' : '' }}>
                                                        <span class="slider"></span>
                                                    </label>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                     
                        <!-- Campaigns & Tags -->
                        <div class="drop-down-container">
                            <select>
                                <option selected disabled>Campaigns</option>
                                <option>Credit Card</option>
                                <option>PayPal</option>
                                <option>Bank Transfer</option>
                            </select>

                            <select class="attributes-drop-down">
                                <option selected disabled>Attributes</option>
                                <option>Credit Card</option>
                                <option>PayPal</option>
                                <option>Bank Transfer</option>
                            </select>

                            <div class="tags-section">
                                <h3>Tags</h3>
                                <div id="tag-container">
                                    <div class="tag">
                                        W-Query
                                        <button class="remove-btn">×</button>
                                    </div>
                                </div>
                                <div class="tag-input">
                                    <select id="tag-select">
                                        <option value="" disabled selected>Select & add tag</option>
                                        <option value="W-Query">W-Query</option>
                                        <option value="Bug">Bug</option>
                                        <option value="Feature">Feature</option>
                                    </select>
                                    <button>+ Add</button>
                                </div>
                                <div class="create-link">Create & Add Tag</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
     const statusIcons = {
        sent: "fas fa-check text-sent",
        delivered: "fa-solid fa-check-double text-sent",
        read: "fa-solid fa-check-double text-read"
    };
</script>
<script>
document.querySelectorAll('.chat-links').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const phone = this.dataset.phone;
        const url = this.href;

        fetch("{{ route('chat.markRead') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // After marking as read, navigate
                window.location.href = url;
            }
        });
    });
});
</script>
<script>
let hasMarkedRead = false;

document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.querySelector('.chat-box');

    if (chatBox) {
        chatBox.addEventListener('scroll', function () {
            // Check if user has scrolled to the bottom (with some threshold)
            const threshold = 50;
            const scrolledToBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - threshold;

            if (scrolledToBottom && !hasMarkedRead) {
                hasMarkedRead = true;

                const phone = '{{ $phone }}'; // make sure this is available in blade

                fetch("{{ route('chat.markRead') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ phone: phone })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        console.log('Messages marked as read.');
                    }
                });
            }
        });
    }
});
</script>




<script>

function chatForm() {
    return {
        phone: '{{ $phone }}',
        message: '',
        file: null,
        fileType: '',
        fileURL: '',
        caption: '',
        isSending: false,

        async send() {
            if (!this.message.trim()) return;

            const response = await fetch('{{ route("send-whatsapp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    phone: this.phone,
                    message: this.message
                })
            });

            if (response.ok) {
                this.message = '';
            } else {
                alert('Failed to send message');
            }
        },

        async sendMedia() {
            if (!this.file) return;
            this.isSending = true;
            const formData = new FormData();
            formData.append('phone', this.phone);
            formData.append('media', this.file);
            formData.append('media_type', this.fileType);
            formData.append('caption', this.caption);

            const response = await fetch('{{ route("send-whatsapp") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            });
            this.isSending = false;

            if (response.ok) {
                this.file = null;
                this.fileType = '';
                this.fileURL = '';
                this.caption = '';
            } else {
                alert('Failed to send media');
            }
        },
        removePreview() {
            this.file = null;
            this.fileType = '';
            this.fileURL = '';
            this.caption = '';
            document.querySelector('.dropup-menu')?.classList.add('hidden'); // hide dropup
        },

         handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.file = file;
            this.fileURL = URL.createObjectURL(file);

            const type = file.type;

           this.fileType = type.startsWith('image/')
            ? 'image'
            : type.startsWith('video/')
            ? 'video'
            : type.startsWith('audio/')
            ? 'audio'
            : ['application/pdf', 'application/msword', 'application/vnd'].some(t => type.startsWith(t))
            ? 'document'
            : 'unknown';
           // Close dropup
            document.querySelector('.dropup-menu')?.classList.add('hidden');
        }
    };
}


// Dropup Toggle
document.addEventListener("DOMContentLoaded", function () {
    const dropupBtn = document.querySelector('.dropup-btn');
    const dropupMenu = document.querySelector('.dropup-menu');

    dropupBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropupMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!dropupMenu.contains(e.target) && !dropupBtn.contains(e.target)) {
            dropupMenu.classList.add('hidden');
        }
    });
});
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

window.Echo.private('chat.{{ $phone }}')
    .listen('.MessageSent', (e) => {
        const chatBox = document.querySelector('.chat-box');
        const msgDiv = document.createElement('div');

        const isOutbound = e.message.direction === 'outbound';
        msgDiv.classList.add('chat-message', isOutbound ? 'me' : 'user');

        if (e.message.wa_message_id) {
            msgDiv.setAttribute('data-wa-id', e.message.wa_message_id);
        }

        // Always insert <i class="status-icon"> even if status is missing
        const iconClass = isOutbound && e.message.status ? statusIcons[e.message.status] : '';
        const statusIcon = isOutbound
            ? `<i class="${iconClass} status-icon"></i>` : '';

        msgDiv.innerHTML = `
            <div class="message-bubble ${isOutbound ? 'me' : 'user'}">
                <p>${e.message.message}</p>
                <div class="message-meta">
                    <small class="message-time">${dayjs().format('D MMM, h:mm A')}</small>
                    ${statusIcon}
                </div>
            </div>
        `;

        chatBox.appendChild(msgDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    });



window.Echo.private('chat.{{ $phone }}')
    .listen('.MessageStatusUpdated', (e) => {
        console.log("📦 Status updated: ", e);

        const updatedMessage = e.message;
        const msgElement = document.querySelector(`[data-wa-id="${updatedMessage.wa_message_id}"]`);
        if (msgElement) {
            const iconEl = msgElement.querySelector('.status-icon');
            if (iconEl && statusIcons[updatedMessage.status]) {
                iconEl.className = `${statusIcons[updatedMessage.status]} status-icon`;
            }
        }
    });


</script>
<script>
      // Chat-specific dropdown (next to chat items)
            window.toggleChatDropdown = (event, btn) => {
                event.stopPropagation(); // Prevent the global click from firing
                const menu = btn.nextElementSibling;

                if (!menu) return;

                // Close all dropdowns first
                document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                    if (dropdown !== menu) {
                        dropdown.style.display = 'none';
                    }
                });

                // Toggle this dropdown
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            };

        //This script truncates long chat preview messages in chat list
        document.addEventListener("DOMContentLoaded", function () {
            const wordLimit = 10.5; // Set your word limit here

            document.querySelectorAll('.chat-list-info p').forEach(p => {
                const text = p.textContent.trim();
                const words = text.split(/\s+/);

                if (words.length > wordLimit) {
                    const trimmed = words.slice(0, wordLimit).join(' ') + '...';
                    p.textContent = trimmed;
                }
            });
        });
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', () => {
            const leftSidebar = document.querySelector('.left-sidebar');
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const chatArea = document.querySelector('.chat-area');

            toggleBtn.addEventListener('click', () => {
                const isHidden = leftSidebar.classList.toggle('sidebar-hidden');

                if (isHidden) {
                    chatArea.classList.add('full-width');
                } else {
                    chatArea.classList.remove('full-width');
                }
            });
        });
    </script>
    
    <script>
        // Toggle right Side bar on profile click js
        const profileSidebar = document.getElementById("profile-details");
        const toggleBtn = document.getElementById("open-profile-btn");
        const closeBtn = document.querySelector(".profile-details-close-btn");

        // Toggle sidebar on profile button click
        toggleBtn.addEventListener("click", () => {
            profileSidebar.classList.toggle("active");
        });

        // Close sidebar on close button click
        closeBtn.addEventListener("click", () => {
            profileSidebar.classList.remove("active");
        });

        
    </script>

@endsection