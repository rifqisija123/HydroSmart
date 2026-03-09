{{-- Chatbot Widget WADAH --}}
{{-- Include this partial before </body> in any blade template --}}

<style>
    /* Floating chat button */
    .wadah-chat-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9000;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, #4aa3ff, #00d4ff);
        box-shadow: 0 6px 24px #4aa3ff55;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .wadah-chat-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 32px #4aa3ff88;
    }

    .wadah-chat-btn svg {
        width: 26px;
        height: 26px;
        fill: #0b1026;
    }

    /* Chat window */
    .wadah-chat-window {
        position: fixed;
        bottom: 92px;
        right: 24px;
        z-index: 9000;
        width: 380px;
        max-width: calc(100vw - 32px);
        height: 520px;
        max-height: calc(100vh - 120px);
        border-radius: 20px;
        overflow: hidden;
        display: none;
        flex-direction: column;
        background: linear-gradient(180deg, #0f1633, #0b1026);
        border: 1px solid #22306b;
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.5), inset 0 1px 0 #ffffff07;
        animation: chatSlideUp 0.3s ease-out;
    }

    .wadah-chat-window.open {
        display: flex;
    }

    @keyframes chatSlideUp {
        from {
            opacity: 0;
            transform: translateY(16px) scale(0.96);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .wadah-chat-window {
            bottom: 0 !important;
            right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100% !important;
            max-height: 100% !important;
            border-radius: 0 !important;
            border: none !important;
        }

        .wadah-chat-window.open~.wadah-chat-btn {
            display: none;
        }

        .wadah-chat-messages {
            padding: 12px;
        }

        .wadah-chat-header {
            padding: 12px 16px;
            padding-top: env(safe-area-inset-top, 12px);
        }

        .wadah-chat-input {
            padding-bottom: env(safe-area-inset-bottom, 12px);
        }
    }

    /* Header */
    .wadah-chat-header {
        padding: 16px;
        background: linear-gradient(135deg, #111b3d, #0d1433);
        border-bottom: 1px solid #22306b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .wadah-chat-header-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4aa3ff, #00d4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .wadah-chat-header-info h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #e8ecff;
    }

    .wadah-chat-header-info span {
        font-size: 11px;
        color: #a8b3ff;
    }

    .wadah-chat-close {
        margin-left: auto;
        background: none;
        border: none;
        color: #a8b3ff;
        font-size: 20px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .wadah-chat-close:hover {
        background: #ffffff12;
    }

    /* Messages */
    .wadah-chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        /* Hide scrollbar for Chrome, Safari and Opera */
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .wadah-chat-messages::-webkit-scrollbar {
        display: none;
    }

    .wadah-msg {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 13px;
        line-height: 1.5;
        word-wrap: break-word;
        animation: msgFade 0.2s ease;
    }

    @keyframes msgFade {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .wadah-msg.user {
        align-self: flex-end;
        background: linear-gradient(135deg, #4aa3ff, #3b8de8);
        color: #0b1026;
        border-bottom-right-radius: 4px;
    }

    .wadah-msg.bot {
        align-self: flex-start;
        background: #161f45;
        color: #e8ecff;
        border: 1px solid #22306b;
        border-bottom-left-radius: 4px;
    }

    .wadah-msg.bot a {
        color: #4aa3ff;
        text-decoration: underline;
        word-break: break-all;
    }

    .wadah-msg.bot a:hover {
        color: #00d4ff;
    }

    /* Typing indicator */
    .wadah-typing {
        display: none;
        align-self: flex-start;
        padding: 10px 16px;
        border-radius: 16px;
        background: #161f45;
        border: 1px solid #22306b;
        border-bottom-left-radius: 4px;
        gap: 4px;
    }

    .wadah-typing.show {
        display: flex;
    }

    .wadah-typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #a8b3ff;
        animation: typingBounce 1.2s ease-in-out infinite;
    }

    .wadah-typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .wadah-typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-6px);
        }
    }

    /* Input */
    .wadah-chat-input {
        padding: 12px 16px;
        border-top: 1px solid #22306b;
        display: flex;
        gap: 8px;
        background: #0d1228;
    }

    .wadah-chat-input textarea {
        flex: 1;
        background: #161f45;
        border: 1px solid #22306b;
        border-radius: 12px;
        padding: 10px 14px;
        color: #e8ecff;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
        resize: none;
        min-height: 40px;
        max-height: 120px;
        font-family: inherit;
        line-height: 1.5;
    }

    .wadah-chat-input textarea::placeholder {
        color: #6877b0;
    }

    .wadah-chat-input textarea:focus {
        border-color: #4aa3ff;
    }

    .wadah-chat-send {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, #4aa3ff, #00d4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .wadah-chat-send:hover {
        transform: scale(1.05);
    }

    .wadah-chat-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .wadah-chat-send svg {
        width: 18px;
        height: 18px;
        fill: #0b1026;
    }

    /* Welcome message */
    .wadah-welcome {
        text-align: center;
        padding: 20px;
        color: #a8b3ff;
        font-size: 12px;
        line-height: 1.6;
    }

    .wadah-welcome-emoji {
        font-size: 32px;
        margin-bottom: 8px;
    }

    /* Suggestion buttons */
    .wadah-suggestions {
        padding: 8px 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        border-top: 1px solid #22306b;
        background: #0d1228;
    }

    .wadah-suggestion-btn {
        background: transparent;
        border: 1px solid #22306b;
        border-radius: 16px;
        color: #a8b3ff;
        font-size: 12px;
        padding: 6px 14px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .wadah-suggestion-btn:hover {
        background: #4aa3ff22;
        border-color: #4aa3ff;
        color: #e8ecff;
    }
</style>

{{-- Chat window --}}
<div class="wadah-chat-window" id="wadahChatWindow">
    <div class="wadah-chat-header">
        <div class="wadah-chat-header-avatar">🤖</div>
        <div class="wadah-chat-header-info">
            <h4>WADAH Bot</h4>
            <span>Tanya seputar aplikasi WADAH</span>
        </div>
        <button class="wadah-chat-close" id="wadahChatClose">✕</button>
    </div>

    <div class="wadah-chat-messages" id="wadahChatMessages">
        <div class="wadah-welcome">
            <div class="wadah-welcome-emoji">👋</div>
            <b style="color:#e8ecff;">Halo! Saya WADAH Bot</b><br>
            Tanya apa saja seputar minuman,<br>harga, atau cara penggunaan WADAH.
        </div>

        {{-- Typing indicator --}}
        <div class="wadah-typing" id="wadahTyping">
            <div class="wadah-typing-dot"></div>
            <div class="wadah-typing-dot"></div>
            <div class="wadah-typing-dot"></div>
        </div>
    </div>

    <div class="wadah-suggestions" id="wadahSuggestions">
        <button class="wadah-suggestion-btn" data-msg="Apa itu WADAH?">Apa itu WADAH?</button>
        <button class="wadah-suggestion-btn" data-msg="Minuman apa saja yang tersedia?">Daftar Minuman</button>
        <button class="wadah-suggestion-btn" data-msg="Berapa harga minumannya?">Harga</button>
        <button class="wadah-suggestion-btn" data-msg="Bagaimana cara menggunakan WADAH?">Cara Pakai</button>
        <button class="wadah-suggestion-btn" data-msg="WADAH berada di mana?">Lokasi</button>
    </div>

    <div class="wadah-chat-input" style="align-items: flex-end;">
        <textarea id="wadahInput" placeholder="Tulis pesan..." rows="1" autocomplete="off"></textarea>
        <button class="wadah-chat-send" id="wadahSendBtn" aria-label="Kirim">
            <svg viewBox="0 0 24 24">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
            </svg>
        </button>
    </div>
</div>

{{-- Floating button --}}
<button class="wadah-chat-btn" id="wadahChatBtn" aria-label="Buka Chat">
    <svg viewBox="0 0 24 24">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z" />
        <path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z" />
    </svg>
</button>

<script>
    (function() {
        // Elements
        const btn = document.getElementById('wadahChatBtn');
        const win = document.getElementById('wadahChatWindow');
        const closeBtn = document.getElementById('wadahChatClose');
        const msgBox = document.getElementById('wadahChatMessages');
        const input = document.getElementById('wadahInput');
        const sendBtn = document.getElementById('wadahSendBtn');
        const typingEl = document.getElementById('wadahTyping');

        // Session — simpan di sessionStorage
        const SESSION_KEY = 'wadah_chat_session';
        const HISTORY_KEY = 'wadah_chat_history';

        function getSessionId() {
            let sid = sessionStorage.getItem(SESSION_KEY);
            if (!sid) {
                sid = 'ses_' + Date.now() + '_' + Math.random().toString(36).slice(2, 8);
                sessionStorage.setItem(SESSION_KEY, sid);
            }
            return sid;
        }

        function getHistory() {
            try {
                return JSON.parse(sessionStorage.getItem(HISTORY_KEY) || '[]');
            } catch {
                return [];
            }
        }

        function saveHistory(history) {
            // Keep max 20 messages in storage
            const trimmed = history.slice(-20);
            sessionStorage.setItem(HISTORY_KEY, JSON.stringify(trimmed));
        }

        // Toggle chat window
        btn.addEventListener('click', () => {
            win.classList.toggle('open');
            if (win.classList.contains('open')) {
                input.focus();
            }
        });
        closeBtn.addEventListener('click', () => win.classList.remove('open'));

        // Convert URLs to clickable links (safe)
        function linkify(text) {
            const urlRegex = /(https?:\/\/[^\s<>"']+)/g;
            return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(urlRegex, '<a href="$1" target="_blank" rel="noopener">$1</a>');
        }

        // Add message to DOM
        function addMessage(text, role) {
            // Hide welcome message once chatting starts
            const welcome = msgBox.querySelector('.wadah-welcome');
            if (welcome) welcome.remove();

            const div = document.createElement('div');
            div.className = 'wadah-msg ' + (role === 'user' ? 'user' : 'bot');
            if (role === 'bot') {
                div.innerHTML = linkify(text);
            } else {
                div.textContent = text;
            }
            msgBox.appendChild(div);
            scrollBottom();
            return div;
        }

        // Create empty bot bubble for streaming
        function createBotBubble() {
            const div = document.createElement('div');
            div.className = 'wadah-msg bot';
            div.textContent = '';
            msgBox.appendChild(div);
            scrollBottom();
            return div;
        }

        function scrollBottom() {
            msgBox.scrollTop = msgBox.scrollHeight;
        }

        function setLoading(on) {
            sendBtn.disabled = on;
            if (on) {
                // Move typing indicator to the end of messages
                msgBox.appendChild(typingEl);
            }
            typingEl.classList.toggle('show', on);
            if (on) scrollBottom();
        }

        // Send message
        async function send() {
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            input.style.height = 'auto'; // Reset height
            addMessage(text, 'user');

            const history = getHistory();
            // Push user message
            history.push({
                role: 'user',
                content: text
            });

            setLoading(true);

            // Only send last 10 messages
            const recentHistory = history.slice(-10);

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrf ? csrf.getAttribute('content') : '';

                const res = await fetch('{{ route('chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        session_id: getSessionId(),
                        message: text,
                        history: recentHistory.slice(0, -
                            1), // exclude current message (sent separately)
                    }),
                });

                if (!res.ok) throw new Error('Server error: ' + res.status);

                // Read SSE stream with typing effect (word-by-word queue)
                const reader = res.body.getReader();
                const decoder = new TextDecoder();
                const botBubble = createBotBubble();
                let fullResponse = '';

                setLoading(false); // hide typing, show bubble

                let buffer = '';
                const wordQueue = [];
                let isRendering = false;
                let streamDone = false;

                // Render kata satu per satu dengan delay (seperti GPT)
                function renderNext() {
                    if (wordQueue.length === 0) {
                        isRendering = false;
                        return;
                    }
                    isRendering = true;
                    const word = wordQueue.shift();
                    fullResponse += word;
                    botBubble.innerHTML = linkify(fullResponse);
                    scrollBottom();
                    setTimeout(renderNext, 50); // 50ms antar kata
                }

                function enqueueWord(word) {
                    wordQueue.push(word);
                    if (!isRendering) {
                        renderNext();
                    }
                }

                while (true) {
                    const {
                        done,
                        value
                    } = await reader.read();
                    if (done) break;

                    buffer += decoder.decode(value, {
                        stream: true
                    });
                    const lines = buffer.split('\n');
                    buffer = lines.pop(); // keep incomplete line

                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            const jsonStr = line.slice(6).trim();
                            if (!jsonStr) continue;
                            try {
                                const data = JSON.parse(jsonStr);
                                if (data.content) {
                                    enqueueWord(data.content);
                                }
                                if (data.error) {
                                    botBubble.innerHTML = linkify('⚠️ ' + data.error);
                                }
                            } catch {}
                        }
                    }
                }

                // Tunggu sampai semua kata selesai di-render
                await new Promise(resolve => {
                    const check = () => {
                        if (wordQueue.length === 0 && !isRendering) {
                            resolve();
                        } else {
                            setTimeout(check, 50);
                        }
                    };
                    check();
                });

                // Save to history
                if (fullResponse) {
                    history.push({
                        role: 'assistant',
                        content: fullResponse
                    });
                }
                saveHistory(history);

            } catch (err) {
                setLoading(false);
                addMessage('⚠️ Gagal menghubungi server. Pastikan chatbot service aktif.', 'bot');
                console.error('Chat error:', err);
            }
        }

        // Auto-resize textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Events
        sendBtn.addEventListener('click', send);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                send();
            }
        });

        // Suggestion buttons
        const suggestionsEl = document.getElementById('wadahSuggestions');
        document.querySelectorAll('.wadah-suggestion-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const msg = btn.getAttribute('data-msg');
                if (msg) {
                    input.value = msg;
                    send();
                }
            });
        });
    })();
</script>
