<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Chat - Gipstore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            padding-top: 100px;
        }

        .header {
            position: fixed;
            top: 0; left: 0; width: 100%;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            z-index: 1000;
            padding: 1.5rem 1rem;
        }

        .header-content {
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 25px;
        }

        .nav-center, .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-link {
            text-decoration: none;
            color: #333;
            font-size: 20px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #6a41e4;
        }

        .services-page {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .services-page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #6a41e4;
            outline: none;
            box-shadow: 0 0 0 3px rgba(106, 65, 228, 0.1);
        }

        .service-order-btn {
            width: 100%;
            background-color: #6a41e4;
            color: #fff;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }

        .service-order-btn:hover {
            background-color: #5632c5;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        #chatContainer {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 8px;
            background: #fafafa;
        }

        .chat-message {
            margin-bottom: 15px;
            display: flex;
        }

        .chat-message.client {
            justify-content: flex-end;
        }

        .chat-message.admin {
            justify-content: flex-start;
        }

        .chat-bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.4;
            position: relative;
        }

        .chat-bubble.client {
            background-color: #e0e7ff;
            color: #1e3a8a;
        }

        .chat-bubble.admin {
            background-color: #f3f4f6;
            color: #111827;
        }

        .chat-time {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: right;
            margin-top: 4px;
        }
         /* Social Footer Styles */
        .social-footer {
            background-color: #6a41e4;
            padding: 30px 0;
            text-align: center;
            color: #fff;
            margin-top: 50px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .social-icon i {
            font-size: 30px;
            background-color: #f8f8f8;
            border-radius: 50%;
            padding: 10px;
            color: #333;
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .social-icon i:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
            background-color: #ffdd57;
            color: #6a41e4;
        }

        .social-footer p {
            font-size: 14px;
            font-weight: 600;
            color: #ffdd57;
            margin: 0;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .orders-table-container {
                overflow-x: auto;
            }
            
            .orders-table {
                min-width: 800px;
            }
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-success {
            background-color: #d4edda;
            color: #0f5132;
        }

        .status-expire,
        .status-cancel,
        .status-deny {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="/images/logo.png" alt="Gipstore Logo">
            </div>
            <div class="nav-center">
                <a href="{{ route('client.dashboard') }}" class="nav-link">Home</a>
                <a href="{{ route('client.layanan') }}" class="nav-link">Layanan</a>
                <a href="{{ route('client.pesanan.index') }}" class="nav-link">Pesanan</a>
                <a href="{{ route('client.chat.index') }}" class="nav-link">Chat</a>
            </div>
            <div class="nav-links">
                <form action="{{ route('client.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none;">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="services-page">
        <h1 class="services-page-title">Live Chat Bantuan</h1>

        <div class="form-container">
            <div class="form-group">
                <label class="form-label">Status Chat</label>
                <span class="badge 
                    @if($chatSession->status == 'active') badge-success 
                    @elseif($chatSession->status == 'pending') badge-warning 
                    @else badge-danger @endif">
                    {{ ucfirst($chatSession->status) }}
                </span>
            </div>

            <div class="form-group" id="chatContainer">
                <div id="messagesContainer">
                    @if ($messages->isEmpty())
                        <div class="text-muted text-center">Belum ada pesan. Mulai percakapan Anda!</div>
                    @else
                        @foreach ($messages as $message)
                            <div class="chat-message {{ $message->sender_type }}">
                                <div class="chat-bubble {{ $message->sender_type }}">
                                    {{ $message->message }}
                                    <div class="chat-time">{{ $message->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            @if($chatSession->status !== 'closed')
            <form id="chat-form" class="mt-4">
                <div class="form-group">
                    <label for="message" class="form-label">Ketik Pesan</label>
                    <textarea id="message" class="form-control" rows="3" placeholder="Tulis sesuatu..." required></textarea>
                </div>
                <input type="hidden" id="chat_session_id" value="{{ $chatSession->id }}">
                <button type="submit" class="service-order-btn">Kirim</button>
            </form>
            @else
                <div class="text-center text-muted mt-3">Sesi chat ini telah ditutup.</div>
            @endif
        </div>
    </main>

    <!-- Social Media Footer -->
    <footer class="social-footer">
        <div class="social-icons">
            <a href="https://www.instagram.com/gipstore.catalogue/" class="social-icon">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="https://wa.me/+6285746178059" class="social-icon">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://discord.gg/ZZatawHNWy" class="social-icon">
                <i class="bi bi-discord"></i>
            </a>
        </div>
        <p>&copy; Copyright by | gipstore 2024</p>
    </footer>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('chat-form');
            const input = document.getElementById('message');
            const messagesContainer = document.getElementById('messagesContainer');
            const chatContainer = document.getElementById('chatContainer');
            const chatSessionId = document.getElementById('chat_session_id').value;
            let lastMessageId = {{ $messages->count() > 0 ? $messages->last()->id : 0 }};

            function scrollBottom() {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }

            scrollBottom();

            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });

            const channel = pusher.subscribe('private-chat.' + chatSessionId);

            channel.bind('ChatMessageSent', function (data) {
                if (data.sender_type === 'admin') fetchNewMessages();
            });

            form?.addEventListener('submit', function (e) {
                e.preventDefault();
                const message = input.value.trim();
                if (!message) return;

                fetch(`{{ route('client.chat.send-message') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        chat_session_id: chatSessionId,
                        message: message
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (messagesContainer.querySelector('.text-muted')) {
                            messagesContainer.innerHTML = '';
                        }

                        const msg = document.createElement('div');
                        msg.className = 'chat-message client';
                        msg.innerHTML = `<div class="chat-bubble client">
                            ${data.message.message}
                            <div class="chat-time">${new Date(data.message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                        </div>`;
                        messagesContainer.appendChild(msg);
                        input.value = '';
                        lastMessageId = data.message.id;
                        scrollBottom();
                    }
                });
            });

            function fetchNewMessages() {
                fetch(`{{ route('client.chat.get-new-messages') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        chat_session_id: chatSessionId,
                        last_message_id: lastMessageId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            const msg = document.createElement('div');
                            msg.className = `chat-message ${message.sender_type}`;
                            msg.innerHTML = `<div class="chat-bubble ${message.sender_type}">
                                ${message.message}
                                <div class="chat-time">${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                            </div>`;
                            messagesContainer.appendChild(msg);
                        });
                        lastMessageId = data.messages[data.messages.length - 1].id;
                        scrollBottom();
                    }
                });
            }

            setInterval(fetchNewMessages, 30000);
        });
    </script>
</body>
</html>
