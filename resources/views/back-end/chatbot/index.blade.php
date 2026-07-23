@extends('layouts.app')

@push('head')
<script>
    // Disable Tailwind preflight so it doesn't break Metronic base styles
    tailwind = {
        config: {
            corePlugins: {
                preflight: false,
            }
        }
    }
</script>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* 🔥 Live Chat Blinking Effect */
    @keyframes pulse-orange {
        0% { background-color: rgba(255, 152, 0, 0.02); }
        50% { background-color: rgba(255, 152, 0, 0.15); }
        100% { background-color: rgba(255, 152, 0, 0.02); }
    }
    .live-active-row {
        animation: pulse-orange 1.5s infinite;
        border-left: 4px solid #ff9800 !important;
    }
    
    /* Sparkline Bar */
    .sparkline-bar { width: 4px; border-radius: 2px; display: inline-block; margin: 0 1px; }

    /* Custom scrollbar for modal chat stream */
    .modal-chat-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .modal-chat-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .modal-chat-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .modal-chat-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-6 px-4 px-md-8">
    
    <!-- Top Header Bar -->
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="bg-indigo-600 text-white p-3 rounded-xl shadow-md">
                <i class="fa-solid fa-robot text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 m-0">AI Chatbot CRM Live Dashboard</h1>
                <p class="text-xs text-slate-500 m-0 mt-0.5">Real-time visitor chats, automated AI leads extraction & human agent takeover</p>
            </div>
        </div>

        <div class="flex items-center space-x-3 text-xs font-bold">
            <button id="sound-toggle-btn" class="bg-amber-100 hover:bg-amber-200 text-amber-800 px-4 py-2 rounded-full transition-all shadow-sm flex items-center gap-2 border-0 cursor-pointer">
                <i class="fa-solid fa-volume-xmark text-sm"></i> <span>Enable Sound</span>
            </button>
            
            <span id="ws-status" class="bg-rose-100 text-rose-600 px-4 py-2 rounded-full flex items-center gap-1.5 font-semibold">
                <i class="fa-solid fa-circle-xmark text-xs"></i> Offline
            </span>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="space-y-6">

        <!-- Online Agents Widget -->
        <div class="bg-white px-5 py-3.5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider m-0 flex items-center gap-2">
                    <i class="fa-solid fa-users text-indigo-500"></i> Online Agents
                </h4>
                <button onclick="changeAgentName()" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold underline bg-transparent border-0 cursor-pointer">
                    <i class="fa-solid fa-user-pen mr-1"></i> Agent: <span id="current-agent-display">{{ $agentName }}</span>
                </button>
            </div>
            <div id="online-agents-list" class="flex flex-wrap gap-2">
                <span class="text-xs text-slate-400 italic">Connecting to live agent network...</span>
            </div>
        </div>

        <!-- Metrics Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex justify-between items-center relative overflow-hidden">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider m-0">Live Chatting Now</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1 mb-0 flex items-center gap-3">
                        <span id="stat-live-users">0</span>
                        <div class="flex items-end h-6 opacity-60">
                            <div class="sparkline-bar bg-emerald-500 h-3"></div>
                            <div class="sparkline-bar bg-emerald-500 h-5"></div>
                            <div class="sparkline-bar bg-emerald-500 h-4"></div>
                            <div class="sparkline-bar bg-emerald-500 h-6"></div>
                            <div class="sparkline-bar bg-emerald-500 h-4"></div>
                        </div>
                    </h3>
                </div>
                <span class="text-emerald-500 text-2xl bg-emerald-50 h-12 w-12 flex items-center justify-center rounded-xl shadow-inner">
                    <i class="fa-solid fa-signal"></i>
                </span>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex justify-between items-center relative overflow-hidden">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider m-0">Total Extracted Leads</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1 mb-0 flex items-center gap-3">
                        <span id="stat-total-leads">0</span>
                        <div class="flex items-end h-6 opacity-60">
                            <div class="sparkline-bar bg-indigo-500 h-2"></div>
                            <div class="sparkline-bar bg-indigo-500 h-4"></div>
                            <div class="sparkline-bar bg-indigo-500 h-3"></div>
                            <div class="sparkline-bar bg-indigo-500 h-5"></div>
                            <div class="sparkline-bar bg-indigo-500 h-6"></div>
                        </div>
                    </h3>
                </div>
                <span class="text-indigo-500 text-2xl bg-indigo-50 h-12 w-12 flex items-center justify-center rounded-xl shadow-inner">
                    <i class="fa-solid fa-database"></i>
                </span>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="flex space-x-2 text-sm font-bold">
            <button onclick="setFilter('all', this)" class="filter-btn bg-slate-800 text-white px-4 py-2 rounded-lg shadow-sm border-0 cursor-pointer transition-all">All Chats</button>
            <button onclick="setFilter('live', this)" class="filter-btn bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-lg shadow-sm cursor-pointer transition-all">🟢 Live Now</button>
            <button onclick="setFilter('hot', this)" class="filter-btn bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-lg shadow-sm cursor-pointer transition-all">🔥 Hot Leads</button>
        </div>

        <!-- Visitor Leads & Live Chats Table -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-address-book text-indigo-500 text-base"></i>
                    <h3 class="font-bold text-slate-700 text-sm m-0">Visitor Leads & Active Chats</h3>
                </div>
                <button onclick="fetchLeadsData()" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold bg-transparent border-0 cursor-pointer flex items-center gap-1">
                    <i class="fa-solid fa-rotate-right"></i> Refresh List
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm m-0">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-xs font-bold uppercase bg-slate-50/70">
                            <th class="p-4">Visitor & Status</th>
                            <th class="p-4">Extracted Requirement</th>
                            <th class="p-4">WhatsApp</th>
                            <th class="p-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body" class="divide-y divide-slate-100 text-slate-700">
                        <tr><td colspan="4" class="p-8 text-center text-slate-400">Loading live chatbot data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Chat Modal -->
<div id="chat-modal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-[99999] transition-opacity">
    <div class="bg-white w-full max-w-3xl h-[620px] rounded-2xl shadow-2xl flex flex-col overflow-hidden relative">
        
        <!-- Modal Header -->
        <div class="bg-indigo-900 text-white p-4 flex justify-between items-center shadow-md z-10">
            <div>
                <h3 class="font-bold text-base flex items-center m-0 text-white" id="modal-visitor-title">
                    Visitor Chat 
                    <span id="ai-status-badge" class="ml-3 text-[10px] bg-green-500 text-white px-2 py-0.5 rounded-full uppercase tracking-wider">AI Handling</span>
                </h3>
                <p class="text-xs text-indigo-200 mt-1 mb-0" id="modal-visitor-session"></p>
            </div>
            <div class="flex items-center space-x-3">
                <button id="btn-takeover" onclick="triggerTakeover()" class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-lg transition-all shadow-sm border-0 cursor-pointer flex items-center gap-1.5">
                    <i class="fa-solid fa-hand"></i> Human Takeover
                </button>
                <button onclick="closeChatModal()" class="text-indigo-200 hover:text-white transition-all text-2xl bg-transparent border-0 cursor-pointer p-0 leading-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Chat Stream Window -->
        <div class="flex-1 bg-slate-50 overflow-y-auto p-6 space-y-4 modal-chat-scrollbar" id="modal-chat-stream">
        </div>

        <!-- Admin Typing Indicator -->
        <div id="admin-typing-indicator" style="display: none;" class="px-4 py-2 bg-slate-100 border-t border-slate-200 text-xs italic text-indigo-700 font-semibold">
            <i class="fa-solid fa-pen-nib mr-2 animate-bounce"></i>Visitor is typing...
        </div>

        <!-- Reply Input Box -->
        <div class="bg-white p-4 border-t border-slate-200">
            <form id="agent-msg-form" class="flex space-x-2 m-0">
                <input type="text" id="agent-input" class="flex-1 border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Type message as human agent..." autocomplete="off">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 rounded-xl font-bold transition-all shadow-sm border-0 cursor-pointer">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const API_BASE = "https://love14-ain-chatbot.hf.space/api";
    
    let myAgentName = localStorage.getItem("ain_agent_name") || @json($agentName) || "Agent";
    localStorage.setItem("ain_agent_name", myAgentName);

    let WS_URL = `wss://love14-ain-chatbot.hf.space/api/ws/agent?agent_name=${encodeURIComponent(myAgentName)}`;
    let ws;
    let activeSessionId = null;
    let isHumanMode = false;
    
    let currentLiveUsers = []; 
    let currentActiveFilter = 'all'; 
    let allLeadsData = []; 
    let lockedSessions = {}; 

    const notifySound = new Audio("https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3");
    const soundBtn = document.getElementById("sound-toggle-btn");
    
    function changeAgentName() {
        const newName = prompt("Enter your Agent Name for Live Chatbot:", myAgentName);
        if (newName && newName.trim() !== "") {
            myAgentName = newName.trim();
            localStorage.setItem("ain_agent_name", myAgentName);
            document.getElementById("current-agent-display").innerText = myAgentName;
            WS_URL = `wss://love14-ain-chatbot.hf.space/api/ws/agent?agent_name=${encodeURIComponent(myAgentName)}`;
            if (ws) {
                ws.close();
            } else {
                initWebSocket();
            }
        }
    }

    // 🔥 Helper function Desktop Notification
    function showDesktopNotification(title, body) {
        if ("Notification" in window && Notification.permission === "granted") {
            if (document.hidden) {
                new Notification(title, {
                    body: body,
                    icon: "https://cdn-icons-png.flaticon.com/512/893/893257.png"
                });
            }
        }
    }

    soundBtn.addEventListener('click', function() {
        notifySound.play().then(() => {
            notifySound.pause();
            notifySound.currentTime = 0;
            soundBtn.innerHTML = `<i class="fa-solid fa-bell text-sm"></i> Alerts Active (${myAgentName})`;
            soundBtn.className = "bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full shadow-sm cursor-default flex items-center gap-2 border-0";
            soundBtn.disabled = true; 
            
            if ("Notification" in window) {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        console.log("Desktop notifications enabled.");
                    }
                });
            }
        }).catch(e => console.log("Audio unlock failed", e));
    });

    function initWebSocket() {
        ws = new WebSocket(WS_URL);

        ws.onopen = () => {
            const status = document.getElementById("ws-status");
            status.innerHTML = `<i class="fa-solid fa-circle-check text-xs"></i> Online as ${myAgentName}`;
            status.className = "bg-green-100 text-green-700 px-4 py-2 rounded-full flex items-center gap-1.5 font-semibold";
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);

            // User typing signal
            if (data.type === "typing") {
                const typingIndicator = document.getElementById("admin-typing-indicator");
                if (data.session_id === activeSessionId) { 
                    if (data.is_typing) {
                        typingIndicator.style.display = "block";
                        const stream = document.getElementById("modal-chat-stream");
                        stream.scrollTop = stream.scrollHeight;
                    } else {
                        typingIndicator.style.display = "none";
                    }
                }
                return; 
            }

            if (data.type === "online_agents_update" || data.type === "init_state") {
                if (data.agents) {
                    const listDiv = document.getElementById("online-agents-list");
                    if (data.agents.length === 0) {
                        listDiv.innerHTML = `<span class="text-xs text-slate-400 italic">No agents online.</span>`;
                    } else {
                        listDiv.innerHTML = data.agents.map(name => `
                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-[11px] font-bold shadow-sm inline-flex items-center">
                                <i class="fa-solid fa-circle text-[8px] mr-1.5 text-emerald-500 animate-pulse"></i> ${name}
                            </span>
                        `).join('');
                    }
                }
            }

            if (data.type === "init_state" || data.type === "user_connected") {
                if (data.takeovers) lockedSessions = data.takeovers; 
                if (data.users) {
                    currentLiveUsers = data.users; 
                    document.getElementById("stat-live-users").innerText = data.users.length;
                    renderTable(); 
                    
                    if (data.type === "user_connected") {
                        showDesktopNotification("New Visitor! 🚀", "A new user just started a chat.");
                    }
                }
            } 
            else if (data.type === "user_disconnected") {
                if (data.session_id) currentLiveUsers = currentLiveUsers.filter(id => id !== data.session_id);
                if (data.users) currentLiveUsers = data.users; 
                document.getElementById("stat-live-users").innerText = currentLiveUsers.length;
                renderTable(); 
            }

            if (data.type === "chat_locked") {
                lockedSessions[data.session_id] = data.agent_name; 
                if (activeSessionId === data.session_id) {
                    isHumanMode = true; 
                    document.getElementById("btn-takeover").style.display = "none"; 
                    const badge = document.getElementById("ai-status-badge");
                    badge.className = "ml-3 text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full uppercase tracking-wider";
                    badge.innerText = `Handled by ${data.agent_name}`;
                    document.getElementById("agent-input").disabled = true;
                    document.getElementById("agent-input").placeholder = `Chat is locked by ${data.agent_name}...`;
                }
                renderTable(); 
            }

            if (data.type === "new_message") {
                if (data.sender === "User") {
                    notifySound.play().catch(e => {});
                    showDesktopNotification("New Message 💬", data.text);
                }
                if (activeSessionId === data.session_id) {
                    const useTypewriter = data.sender !== "Agent";
                    appendMessageToUI(data.sender, data.text, useTypewriter);
                }
            }

            if (data.type === "takeover_status" && data.session_id === activeSessionId) {
                isHumanMode = true;
                lockedSessions[activeSessionId] = data.agent_name;
                updateTakeoverUI();
                renderTable();
            }
        };

        ws.onclose = () => {
            const status = document.getElementById("ws-status");
            status.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-xs"></i> Disconnected`;
            status.className = "bg-rose-100 text-rose-700 px-4 py-2 rounded-full flex items-center gap-1.5 font-semibold";
            document.getElementById("online-agents-list").innerHTML = `<span class="text-xs text-rose-400 italic">Disconnected from server. Retrying...</span>`;
            setTimeout(initWebSocket, 3000); 
        };
    }

    function setFilter(filterType, btnEl) {
        currentActiveFilter = filterType;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.className = "filter-btn bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-lg shadow-sm cursor-pointer transition-all";
        });
        btnEl.className = "filter-btn bg-slate-800 text-white px-4 py-2 rounded-lg shadow-sm border-0 cursor-pointer transition-all";
        renderTable(); 
    }

    async function fetchLeadsData() {
        try {
            const res = await fetch(`${API_BASE}/admin/leads`);
            allLeadsData = await res.json();
            document.getElementById("stat-total-leads").innerText = allLeadsData.length;
            renderTable();
        } catch (err) { console.error("Error fetching leads data:", err); }
    }

    function renderTable() {
        const tableBody = document.getElementById("history-table-body");
        tableBody.innerHTML = "";

        if (allLeadsData.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-slate-400">No leads found in database.</td></tr>`;
            return;
        }

        allLeadsData.forEach(lead => {
            const isLive = currentLiveUsers.includes(lead.session_id);
            const hasPhone = lead.phone ? true : false;

            if (currentActiveFilter === 'live' && !isLive) return;
            if (currentActiveFilter === 'hot' && !hasPhone) return;

            const visitorName = lead.name ? lead.name : `Visitor (${lead.session_id.substring(0, 8)})`;
            
            let badges = '';
            if (lead.service) badges += `<span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded border border-purple-200 text-[10px] font-bold mr-1 inline-block mb-1">${lead.service}</span>`;
            if (lead.subject) badges += `<span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded border border-blue-200 text-[10px] font-bold mr-1 inline-block mb-1">${lead.subject}</span>`;
            if (lead.deadline) badges += `<span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded border border-rose-200 text-[10px] font-bold mr-1 inline-block mb-1"><i class="fa-regular fa-clock mr-1"></i>${lead.deadline}</span>`;
            const detailsSummary = badges !== '' ? badges : '<span class="text-slate-400 italic text-xs">Exploring...</span>';

            const rowClass = isLive ? "live-active-row bg-white border-l-4 border-transparent" : "hover:bg-slate-50/80 transition-all border-l-4 border-transparent";
            const statusTag = isLive ? `<span class="ml-2 text-[9px] bg-green-500 text-white px-2 py-0.5 rounded-full font-bold animate-pulse">LIVE</span>` : '';
            
            const handlingAgent = (lead.handled_by && lead.handled_by !== "AI") ? lead.handled_by : lockedSessions[lead.session_id];
            let handlerTag = `<span class="mt-1 inline-block text-[10px] text-slate-500 font-medium"><i class="fa-solid fa-robot mr-1"></i>AI Handled</span>`;
            
            if (handlingAgent) {
                const colorClass = handlingAgent === myAgentName ? "text-amber-600" : "text-red-500";
                handlerTag = `<span class="mt-1 inline-block text-[10px] ${colorClass} font-bold"><i class="fa-solid fa-user-lock mr-1"></i>Handled by ${handlingAgent}</span>`;
            }

            const tr = document.createElement("tr");
            tr.className = `${rowClass} cursor-pointer`;
            tr.onclick = () => openChatModal(lead.session_id, visitorName);
            tr.innerHTML = `
                <td class="p-4">
                    <div class="font-semibold text-indigo-600 flex items-center text-sm">${visitorName} ${statusTag}</div>
                    ${handlerTag}
                </td>
                <td class="p-4">${detailsSummary}</td>
                <td class="p-4">
                    ${lead.phone ? `<span class="bg-green-100 text-green-700 font-bold text-xs px-2.5 py-1 rounded-md inline-flex items-center gap-1"><i class="fa-brands fa-whatsapp"></i>${lead.phone}</span>` : '<span class="text-slate-300">-</span>'}
                </td>
                <td class="p-4 text-center">
                    <button class="bg-slate-100 hover:bg-indigo-100 text-slate-700 hover:text-indigo-700 text-[11px] uppercase font-bold px-3 py-1.5 rounded border border-slate-200 transition-all shadow-sm border-0 cursor-pointer">
                        Open Chat
                    </button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    async function openChatModal(sessionId, visitorName) {
        activeSessionId = sessionId;
        const leadObj = allLeadsData.find(l => l.session_id === sessionId);
        const dbHandler = (leadObj && leadObj.handled_by && leadObj.handled_by !== "AI") ? leadObj.handled_by : null;
        const lockerName = dbHandler || lockedSessions[sessionId];
        
        isHumanMode = lockerName ? true : false;
        
        const badgeClass = lockerName ? (lockerName === myAgentName ? "bg-amber-500" : "bg-red-500") : "bg-green-500";
        const badgeText = lockerName ? (lockerName === myAgentName ? "Human Mode" : `Handled by ${lockerName}`) : "AI Handling";

        document.getElementById("modal-visitor-title").innerHTML = `${visitorName} <span id="ai-status-badge" class="ml-3 text-[10px] ${badgeClass} text-white px-2 py-0.5 rounded-full uppercase tracking-wider">${badgeText}</span>`;
        document.getElementById("modal-visitor-session").innerText = `Session ID: ${sessionId}`;
        document.getElementById("chat-modal").classList.remove("hidden");

        const inputField = document.getElementById("agent-input");
        const takeoverBtn = document.getElementById("btn-takeover");
        
        if (lockerName && lockerName !== myAgentName) {
            takeoverBtn.style.display = "none";
            inputField.disabled = true;
            inputField.placeholder = `Chat is locked by ${lockerName}...`;
        } else {
            takeoverBtn.style.display = lockerName === myAgentName ? "none" : "flex";
            inputField.disabled = false;
            inputField.placeholder = "Type message as human agent...";
        }

        document.getElementById("admin-typing-indicator").style.display = "none";
        const stream = document.getElementById("modal-chat-stream");
        stream.innerHTML = `<div class="text-center text-slate-400 text-xs py-10">Loading conversation history...</div>`;

        try {
            const res = await fetch(`${API_BASE}/admin/chats/${sessionId}`);
            const messages = await res.json();
            stream.innerHTML = "";
            messages.forEach(msg => {
                if (msg.message.includes('"type":') && (msg.message.includes('"typing"') || msg.message.includes('"admin_typing"'))) return;
                appendMessageToUI(msg.sender, msg.message, false);
            });
        } catch(e) { console.error("Error loading chat history:", e); }
    }

    function closeChatModal() {
        document.getElementById("chat-modal").classList.add("hidden");
        activeSessionId = null;
    }

    function appendMessageToUI(sender, text, useTypewriter = false) {
        const stream = document.getElementById("modal-chat-stream");
        const isBotOrAgent = sender.toLowerCase() === "bot" || sender.toLowerCase() === "agent";
        
        const align = isBotOrAgent ? "justify-start" : "justify-end";
        const bubbleColor = isBotOrAgent ? "bg-white text-slate-800 border border-slate-200 rounded-bl-none shadow-sm" : "bg-indigo-600 text-white rounded-br-none shadow-md";
        const icon = sender.toLowerCase() === "bot" ? "🤖 " : (sender.toLowerCase() === "agent" ? "🧑‍💻 " : "");

        const wrapper = document.createElement("div");
        wrapper.className = `flex ${align}`;
        wrapper.innerHTML = `
            <div class="max-w-md p-3.5 rounded-2xl text-sm leading-relaxed ${bubbleColor}">
                <p class="msg-text-content m-0"></p>
            </div>
        `;
        stream.appendChild(wrapper);
        
        const pTag = wrapper.querySelector(".msg-text-content");
        const fullText = icon + text;

        if (useTypewriter) {
            let i = 0;
            function type() {
                if (i < fullText.length) {
                    pTag.textContent += fullText.charAt(i);
                    i++;
                    stream.scrollTop = stream.scrollHeight;
                    setTimeout(type, 25);
                }
            }
            type();
        } else {
            pTag.textContent = fullText;
            stream.scrollTop = stream.scrollHeight;
        }
    }

    function triggerTakeover() {
        if (!ws || ws.readyState !== WebSocket.OPEN) return alert("WebSocket is not connected!");
        ws.send(JSON.stringify({
            action: "takeover",
            session_id: activeSessionId,
            agent_name: myAgentName 
        }));
    }

    function updateTakeoverUI() {
        document.getElementById("btn-takeover").style.display = "none"; 
        const badge = document.getElementById("ai-status-badge");
        badge.className = "ml-3 text-[10px] bg-amber-500 text-white px-2 py-0.5 rounded-full uppercase tracking-wider";
        badge.innerText = "Human Mode";
    }

    let isAdminTyping = false;
    let adminTypingTimer;
    let isAdminTypingSignalSent = false;

    document.getElementById("agent-input").addEventListener('input', function() {
        if (!activeSessionId || ws.readyState !== WebSocket.OPEN) return;
        
        isAdminTyping = true;
        if (!isAdminTypingSignalSent) {
            isAdminTypingSignalSent = true;
            ws.send(JSON.stringify({ action: "admin_typing", session_id: activeSessionId, is_typing: true }));
        }
        
        clearTimeout(adminTypingTimer);
        adminTypingTimer = setTimeout(() => {
            isAdminTyping = false;
            isAdminTypingSignalSent = false;
            ws.send(JSON.stringify({ action: "admin_typing", session_id: activeSessionId, is_typing: false }));
        }, 2000);
    });

    document.getElementById("agent-msg-form").addEventListener("submit", function(e) {
        e.preventDefault();
        const input = document.getElementById("agent-input");
        const text = input.value.trim();
        
        if (text && activeSessionId && ws.readyState === WebSocket.OPEN) {
            if (!isHumanMode) triggerTakeover();

            clearTimeout(adminTypingTimer);
            isAdminTyping = false;
            isAdminTypingSignalSent = false;
            ws.send(JSON.stringify({ action: "admin_typing", session_id: activeSessionId, is_typing: false }));

            ws.send(JSON.stringify({
                action: "send_message",
                session_id: activeSessionId,
                text: text
            }));
            input.value = "";
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        initWebSocket();
        fetchLeadsData();
        setInterval(fetchLeadsData, 5000);
    });
</script>
@endsection
