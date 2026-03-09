<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Dibatalkan — WADAH</title>

    <link rel="icon" type="image/png" href="{{ asset('img/wadahcircle.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/wadahcircle.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: "#0b1026",
                        card: "#111735",
                        border: "#22306b",
                        primary: "#4aa3ff",
                        accent: "#00d4ff",
                        text: "#e8ecff",
                        danger: "#ff4a4a"
                    }
                }
            }
        }
    </script>

    <style>
        body {
            color: #e8ecff;
            background: #0b1026;
            background-image:
                radial-gradient(at 80% 0%, hsla(227, 72%, 48%, 0.25) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(190, 100%, 50%, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, hsla(227, 72%, 48%, 0.1) 0px, transparent 50%);
            background-attachment: fixed;
            background-size: 200% 200%;
            animation: flow 15s ease infinite;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }

        @keyframes flow {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }

        .cardish {
            background: linear-gradient(180deg, #0f1633, #0b1026);
            box-shadow: 0 10px 30px #0008, inset 0 1px 0 #ffffff07;
        }

        .pill {
            border: 1px solid #22306b;
            background: #ffffff12;
            color: #cfe3ff;
        }

        /* Animasi X merah */
        @keyframes popPulseRed {
            0% {
                transform: scale(0.6);
                opacity: 0;
                filter: drop-shadow(0 0 0 rgba(239, 68, 68, 0));
            }

            10% {
                transform: scale(1.15);
                opacity: 1;
                filter: drop-shadow(0 8px 24px rgba(239, 68, 68, .45));
            }

            18% {
                transform: scale(1.0);
                opacity: 1;
            }

            70% {
                transform: scale(1.0);
                opacity: 1;
            }

            100% {
                transform: scale(0.98);
                opacity: 1;
                filter: drop-shadow(0 4px 12px rgba(239, 68, 68, .25));
            }
        }

        .animate-pop-red-3s {
            animation: popPulseRed 3s ease-out infinite;
            transform-origin: center;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-bg">

    <!-- PRELOADER -->
    <div id="preloader"
        style="
         position: fixed;
         top: 0; left: 0;
         width: 100%; height: 100vh;
         background: rgba(0,0,0,0.65);
         backdrop-filter: blur(2px);
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         z-index: 9999;
       ">

        <img src="{{ asset('img/wadahcircle.png') }}" alt="Loading Wadah..."
            style="width:120px; height:120px; animation:spin 2s linear infinite;">

        <p style="color:#cfe3ff; margin-top:20px; font-size:18px; letter-spacing:1px;">
            Loading...
        </p>
    </div>

    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    {{-- HEADER --}}
    <header class="sticky top-0 z-40 backdrop-blur bg-[#0b1026e6] border-b border-border">
        <div class="max-w-5xl mx-auto px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/wadahlogo.png') }}" alt="WADAH Logo" width="60">
                <div>
                    <div class="font-bold tracking-wide">Inovasi <span class="text-accent">Wadah</span></div>
                    <div class="text-sm text-[#a8b3ff]">Pembayaran Dibatalkan</div>
                </div>
            </div>
            <a href="{{ route('home') }}">
                <span class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">Beranda</span>
            </a>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="flex-grow w-full mx-auto px-6 py-12 max-w-lg md:max-w-xl">
        <div class="cardish border border-border rounded-2xl p-8 text-center">

            {{-- Icon X merah --}}
            <div
                class="mx-auto w-16 h-16 rounded-full bg-danger flex items-center justify-center mb-4 animate-pop-red-3s">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold mb-2">Pembayaran Dibatalkan</h1>
            <p class="text-[#a8b3ff] mb-6">
                Anda membatalkan pembayaran untuk minuman <b>{{ ucfirst(request()->query('drink', '—')) }}</b>
                dengan volume <b>{{ request()->query('ml', '—') }} ml</b>.
            </p>

            <div class="text-left bg-[#0b1d40]/70 border border-[#2b3e7a] rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-2">
                    <div class="text-red-400 mt-[3px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-[#cfd9ff] leading-relaxed">
                        Anda dapat memilih menu lain atau mencoba kembali pembayaran jika masih ingin melanjutkan.
                    </p>
                </div>
            </div>

            <a href="{{ route('home') }}"
                class="block w-full bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] text-[#0b1026]
                font-semibold rounded-xl py-3 hover:opacity-90 transition">
                Ubah Menu
            </a>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-border py-4 text-center text-[#a8b3ff] mt-auto">
        © {{ date('Y') }} WADAH • UI Smart Drinking. Dibuat Tim Inovasi Teknologi Metatech.
    </footer>

    @include('partials.chatbot')

    <script>
        window.addEventListener('load', () => {
            const p = document.getElementById('preloader');

            setTimeout(() => {
                p.style.opacity = "0";
                p.style.transition = "opacity 0.5s ease";

                setTimeout(() => p.style.display = "none", 500);
            }, 1000);
        });
    </script>
</body>

</html>
