<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@extends('layouts.app')

@section('content')
    {{-- @php
        $data = [
            'Status' => 'Closed',
            'Last Active' => '5/1/2025, 1:00 PM',
            'Template Messages' => 0,
            'Session Messages' => 20,
            'Unresolved Queries' => 0,
            'Source' => 'ORGANIC',
            'First Message' => '.....',
            'WA Conversation' => 'Inactive',
            'MAU Status' => 'Active',
            'Incoming' => 'Allowed',
            'Opted In' => true,
        ];
    @endphp --}}
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
                        <input type="radio" name="tabs" class="tab-input" id="tab-active">

                        <!-- Tab Labels -->
                        <div class="tab-labels">
                            <label for="tab-all" class="tab-label">All</label>
                            <label for="tab-read" class="tab-label">Read</label>
                            <label for="tab-unread" class="tab-label">Unread</label>
                            <label for="tab-active" class="tab-label">Active</label>
                        </div>

                        <!-- Tab Contents -->
                        <div class="tabs-content">
                            <div class="tab-content" id="content-all"> <i class="fa-solid fa-envelope"></i>
                                Showing all messages
                            </div>
                            <div class="tab-content" id="content-read"> <i class="fa-solid fa-check-circle"></i>
                                Showing read messages
                            </div>
                            <div class="tab-content" id="content-unread"> <i class="fa-solid fa-envelope-open"></i>
                                Showing unread messages
                            </div>
                            <div class="tab-content" id="content-active"> <i class="fa-solid fa-fire-flame-curved"></i>
                                Showing active messages
                            </div>
                        </div>

                    </div>

                    <!-- Chats List -->
                    <div class="chats-bar">
                        @foreach ($usersArray as $user)
                            @php
                                $mergedExtras = $user['extra'];
                                $lastChat = end($user['chats']);
                                $previewMessage = $user['message'] ?? '';
                                $previewTime = $user['time'] ?? '';
                            @endphp

                            <div class="chat-item" onclick="loadChat(this)" data-id="{{ $user['id'] }}"
                                data-name="{{ $user['name'] }}" data-number="{{ $user['Phone'] }}" data-status="{{ $user['time'] }}" data-lastseen="{{ 'Last seen today at '.$user['time'] }}" data-avatar="{{ $user['avatar'] }}" data-extra='@json($mergedExtras)' data-chats='@json($user['chats'])'>

                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}">

                                <div class="chat-list-info">
                                    <div class="chat-list-header">
                                        <h4>{{ $user['name'] }}</h4>
                                         <span class="message-time">{{ $previewTime }}</span>
                                    </div>
                                   <div class="message-info">
                                        <p>{{ $previewMessage }}</p>
                                        @php
                                            $unreadCount = $user['unread_count'] ?? 0;
                                            $isUnread = $user['is_unread'] ?? false; // flag to show empty bubble
                                        @endphp

                                        @if ($unreadCount > 0)
                                            <div class="unread-bubble">{{ $unreadCount }}</div>
                                        @elseif ($isUnread)
                                            <div class="unread-bubble empty"></div>
                                        @endif
                                    </div>
                                </div>

                                <div class="chat-dropdown">
                                    <a class="dots-btn" onclick="toggleChatDropdown(event, this)">
                                        <!-- <i class="fas fa-ellipsis-v"></i> -->
                                          <i class="fas fa-chevron-down fa-lg"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <button onclick="closeChat(this)">Close chat</button>
                                        <button onclick="markUnread(this)">Mark as unread</button>
                                        <hr>
                                        <button onclick="showContactInfo(this)">Contact info</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-area">

                <!-- Chat Header -->
                <!-- Chat Header -->
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
                                        <h3>Select a Chat</h3>
                                        <p>No Last seen Available</p>
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


                <!-- Main Chat Body with Scroll -->
                <div class="chat-body">
                    <!-- Scrollable Chat Messages -->
                    {{-- <div class="chat-box" data-chats='@json($usersArray[0]["chats"])'> --}}
                  <div class="chat-box">
                        {{-- @foreach($usersArray[0]["chats"] as $msg)
                            <div class="message {{ $msg['from'] === 'inbound' ? 'incoming' : 'outgoing' }}">
                                {{ $msg['message'] }}
                                <div style="font-size: 10px; color: #666; margin-top: 4px;">
                                    {{ $msg['time'] }}
                                </div>
                            </div>
                        @endforeach --}}
                    </div>
                </div>

                <!-- Message Input Section -->
                
                <form id="chatForm" action="{{ route('send-whatsapp') }}" method="POST">
                @csrf
                    <input type="hidden" name="phone" id="chatPhoneInput" value="">
                    <div class="message-input-container">
                        <div class="input-actions" style="position: relative;">
                            <button class="emoji-btn"><i class="fa-solid fa-face-smile fa-sm"></i></i></button>
                            <div class="dropup-wrapper">
                                <button class="dropup-btn"><i class="fa-solid fa-paperclip fa-sm"></i></button>

                                <!-- Dropup Menu -->
                                <div class="dropup-menu hidden">
                                    <div><i class="fa fa-folder fa-fw icon-blue"></i> File</div>
                                    <div><i class="fa fa-image fa-fw icon-blue"></i> Photos & videos</div>
                                    <div><i class="fa fa-user fa-fw icon-blue"></i> Contact</div>
                                    <div><i class="fa fa-bars fa-fw icon-blue"></i> Poll</div>
                                    <div><i class="fa fa-calendar fa-fw icon-blue"></i> Event</div>
                                </div>
                            </div>
                        </div>

                        <!-- Emoji Picker Popup -->
                        <div class="emoji-picker hidden">
                            <div class="emoji-categories"></div>
                            <div class="emoji-container"></div>
                        </div>
                        <div class="message-input">
                            <input type="text" placeholder="Type a message" name="message">
                        </div>
                        <div class="send-btn">
                            <button type="submit"><i class="fa-solid fa-paper-plane fa-sm" style="color: #4CAF50;"></i></button>
                        </div>
                    </div>
                </form>
                </div>

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
                            <h3 id="profile-details-name">Select a chat</h3>
                            <p id="profile-details-status">Last seen</p>
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
                                @foreach ($mergedExtras as $key => $value)
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
<script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
<script>
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("PUSHER_APP_KEY") }}',
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        forceTLS: true,
        encrypted: true,
    });
     const statusIcons = {
        sent: "fas fa-check text-sent",
        delivered: "fa-solid fa-check-double text-sent",
        read: "fa-solid fa-check-double text-read"
    };
</script>

    <script>
        //This script truncates long chat preview messages in chat list
        document.addEventListener("DOMContentLoaded", function () {
            const wordLimit = 6.5; // Set your word limit here

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
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('hidden');
            }

            // ======= DROPDOWN LOGIC =======
            function closeAllDropdowns() {
                document.querySelectorAll('.dropdown-menu, #dropdownMenu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }

            // General dropdown (e.g., user menu)
            window.toggleDropdown = function (event) {
                event?.stopPropagation(); // Optional if you bind via inline
                const dropdown = document.getElementById("dropdownMenu");
                if (dropdown) {
                    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
                }
            };

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

            // Global listener to close all dropdowns on outside click
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                    dropdown.style.display = 'none';
                });
            });


            // Close any open dropdowns on outside click
            document.addEventListener("click", function (event) {
                const generalDropdown = document.getElementById("dropdownMenu");
                const generalBtn = document.querySelector(".dropdown-btn");

                const isClickOutsideGeneral = generalDropdown && generalBtn &&
                    !generalBtn.contains(event.target) &&
                    !generalDropdown.contains(event.target);

                if (isClickOutsideGeneral) {
                    generalDropdown.style.display = "none";
                }

                closeAllDropdowns(); // closes all chat dropdowns
            });

            // ======= DUMMY ACTIONS =======
            window.closeChat = (btn) => {
                alert("Chat closed for: " + btn.closest('.chat-item')?.dataset.name);
            };

            window.markUnread = (btn) => {
                const chatItem = btn.closest('.chat-item');
                const userName = chatItem?.dataset.name;
                const messageInfo = chatItem.querySelector('.message-info');
                const existingBubble = messageInfo.querySelector('.unread-bubble');

                if (existingBubble) {
                    existingBubble.remove(); // Remove unread bubble
                    chatItem.classList.remove('is-unread');
                    btn.textContent = "Mark as unread";  // Change button text back
                    alert("Marked as read: " + userName);
                } else {
                    const bubble = document.createElement('div');
                    bubble.className = 'unread-bubble empty';
                    messageInfo.appendChild(bubble);
                    chatItem.classList.add('is-unread');
                    btn.textContent = "Mark as read";  // Change button text on unread
                    alert("Marked as unread: " + userName);
                }
            };


            window.showContactInfo = (btn) => {
                const data = JSON.parse(btn.closest('.chat-item')?.dataset.extra || '{}');
                alert(`Phone: ${data.Phone}\nEmail: ${data.Email}\nOpted In: ${data["Opted In"]}`);
            };
            // Load Js here
            // ======= LOAD CHAT =======
        window.loadChat = (element) => {

    // if (window.currentEchoChannel) {
    //     window.Echo.leave(window.currentEchoChannel);
    // }

    // Handle single selection of chat items
    function handleChatSelection(clickedItem) {
        const currentlySelected = document.querySelector('.chat-item.selected');
        if (currentlySelected && currentlySelected !== clickedItem) {
            currentlySelected.classList.remove('selected');
        }
        clickedItem.classList.add('selected');
    }

    // Bind click event
    document.querySelectorAll('.chat-item').forEach(item => {
        item.addEventListener('click', function () {
            handleChatSelection(this);
        });
    });

    // Extract and update data
    const { name, avatar, lastseen, extra, chats, number } = element.dataset;
    document.getElementById('chatPhoneInput').value = number;
    const extraData = JSON.parse(extra);
    const chatData = JSON.parse(chats);

    // Header & profile
    document.querySelector('.chat-details h3').textContent = name;
    document.querySelector('.chat-details p').textContent = lastseen;
    document.querySelector('.chat-avatar img').src = avatar;
    document.getElementById('profile-details-name').textContent = name;
    document.getElementById('profile-details-status').textContent = lastseen;
    document.getElementById('profile-details-avatar').src = avatar;

    // Info table
    const infoWrapper = document.querySelector('.info-table-wrapper');
    let tableHTML = `<table class="info-table">
        <thead><tr><th>Property</th><th>Value</th></tr></thead><tbody>`;

    for (let key in extraData) {
        let value = extraData[key];
        if (key === 'Opted In') {
            value = `
                <label class="switch">
                    <input type="checkbox" disabled ${extraData[key] ? 'checked' : ''}>
                    <span class="slider"></span>
                </label>`;
        }
        tableHTML += `<tr><td>${key}</td><td>${value}</td></tr>`;
    }
    tableHTML += `</tbody></table>`;
    infoWrapper.innerHTML = tableHTML;

    // Chat rendering
    const chatBox = document.querySelector('.chat-box');
    chatBox.innerHTML = '';

   

    let allChats = [...chatData];
    let loadedMessages = 20;
    const chunkSize = 20;

    function getLatestChats() {
        return allChats.slice(Math.max(0, allChats.length - loadedMessages));
    }

    function renderChatMessages(prepend = false) {
        const chatsToRender = getLatestChats();
        const scrollOffset = chatBox.scrollHeight - chatBox.scrollTop;

        if (!prepend) chatBox.innerHTML = '';

        chatsToRender.forEach(chat => {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'chat-message ' + (chat.from === 'me' ? 'me' : 'user');

            const statusIcon = chat.from === 'me' && chat.status
                ? `<i class="${statusIcons[chat.status] || ''} status-icon"></i>` : '';

             msgDiv.innerHTML = `
                    <div class="message-bubble">
                        <p>${chat.message}</p>
                        <div class="message-meta">
                            <small class="message-time">${chat.time}</small>
                            ${statusIcon}
                        </div>
                    </div>
                `;

            prepend ? chatBox.prepend(msgDiv) : chatBox.appendChild(msgDiv);
        });

        chatBox.scrollTop = prepend
            ? chatBox.scrollHeight - scrollOffset
            : chatBox.scrollHeight;
    }

    chatBox.addEventListener('scroll', () => {
        if (chatBox.scrollTop === 0 && loadedMessages < allChats.length) {
            loadedMessages += chunkSize;
            renderChatMessages(true);
        }
    });

    renderChatMessages();

    // ====== REAL-TIME MESSAGE LISTENING VIA ECHO ======
    const channelName = `chat.${number}`;
    window.currentEchoChannel = `private-${channelName}`;

    window.Echo.private(channelName)
        .listen('.MessageSent', (e) => {
            const msg = e.message;
            const isMe = msg.direction === 'outbound' || msg.from === 'me';
            const iconKey = msg.status || 'sent';
            const statusIconHtml = isMe ? `<i class="${statusIcons[iconKey] || ''} status-icon"></i>` : '';
            const msgDiv = document.createElement('div');
            msgDiv.className = 'chat-message ' + (isMe ? 'me' : 'user');
            msgDiv.innerHTML = `
                <div class="message-bubble">
                    <p>${msg.message}</p>
                    <div class="message-meta">
                        <small class="message-time">${dayjs().format('D MMM, h:mm A')}</small>
                        ${statusIconHtml}
                    </div>
                </div>
                `;
            chatBox.appendChild(msgDiv);
            chatBox.scrollTop = chatBox.scrollHeight;

             // Also update your local chat array
            // allChats.push({
            //     from: isMe ? 'me' : 'user',
            //     message: msg.message,
            //     time: dayjs().format('D MMM, h:mm A'),
            //     status: msg.status || 'sent'
            // });
        });
    };       
});
    </script>
<script>
    document.getElementById('chatForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const message = formData.get('message');
        const phone = formData.get('phone');
        const sendBtn = form.querySelector('button[type="submit"]');
        sendBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(async response => {
            if (!response.ok) {
                const text = await response.text();
                throw new Error(text || 'Network error');
            }
            // console.log('response: '+response);
            return response.json();
        })
        .then(data => {
            sendBtn.disabled = false;
            // console.log('response 1: '+data);
            if (data.success) {
                const newMessage = {
                from: 'me',
                message: message,
                time: new Date().toLocaleTimeString(),
                status: 'sent' // or 'delivered' or 'read' if you have tracking
            };
             // Update your global or current chat object
            // if (window.currentChat && Array.isArray(window.currentChat.chats)) {
            //     window.currentChat.chats.push(newMessage);
            // }
            //     const chatBox = document.querySelector('.chat-message');
            //     const messageHtml = `
            //         <div class="message-bubble">
            //             <p>${formData.get('message')}</p>
            //             <small>${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })} </small>
            //         </div>
            //     `;
                 

            //     chatBox.innerHTML += messageHtml;
            //     chatBox.scrollTop = chatBox.scrollHeight;

                form.querySelector('input[name="message"]').value = '';
            } else {
                alert('Failed to send: ' + (data.error || 'Unknown error.'));
            }
        })
        .catch(error => {
            sendBtn.disabled = false;
            alert('Error sending message.');
            console.error(error);
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
        document.addEventListener("DOMContentLoaded", function () {
            const dropupBtn = document.querySelector('.dropup-btn');
            const dropupMenu = document.querySelector('.dropup-menu');

            // Toggle menu
            dropupBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropupMenu.classList.toggle('hidden');
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!dropupMenu.contains(e.target) && !dropupBtn.contains(e.target)) {
                    dropupMenu.classList.add('hidden');
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
        const tabInputs = document.querySelectorAll(".tab-input");
        const tabContents = document.querySelectorAll(".tab-content");
        const tabLabels = document.querySelectorAll(".tab-label");

        // Function to show active tab content
        function updateActiveTab() {
            tabInputs.forEach((input, index) => {
                if (input.checked) {
                    // Show corresponding tab content
                    tabContents.forEach(content => content.style.display = "none");
                    tabContents[index].style.display = "block";

                    // Add active class to the selected label
                    tabLabels.forEach(label => label.classList.remove("active"));
                    tabLabels[index].classList.add("active");
                }
            });
        }

    // Initial setup
    updateActiveTab();

    // Add event listeners
    tabInputs.forEach(input => {
        input.addEventListener("change", updateActiveTab);
    });
});
</script>

@endsection