<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil — WADAH</title>

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
                        text: "#e8ecff"
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

        /* Hilangkan scrollbar untuk area yang bisa digeser horizontal */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Animasi ceklist: pop in lalu tenang, ulang tiap 3s */
        @keyframes popPulse {
            0% {
                transform: scale(0.6);
                opacity: 0;
                filter: drop-shadow(0 0 0 rgba(34, 197, 94, 0));
            }

            10% {
                transform: scale(1.15);
                opacity: 1;
                filter: drop-shadow(0 8px 24px rgba(34, 197, 94, .45));
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
                filter: drop-shadow(0 4px 12px rgba(34, 197, 94, .25));
            }
        }

        .animate-pop-3s {
            animation: popPulse 3s ease-out infinite;
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
                <img src="{{ asset('img/wadahlogo.png') }}" alt="Water Automation Dispenser Aman Hemat" width="60">
                <div>
                    <div class="font-bold tracking-wide">Inovasi <span class="text-accent">Wadah</span></div>
                    <div class="text-sm text-[#a8b3ff]">Pembayaran Success</div>
                </div>
            </div>
            <a href="{{ route('home') }}">
                <span class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">Beranda</span>
            </a>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow w-full mx-auto px-6 py-12 max-w-lg md:max-w-xl">
        <div class="cardish border border-border rounded-2xl p-8 text-center">

            {{-- Icon Success + animasi --}}
            <div
                class="mx-auto w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-4 animate-pop-3s">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold mb-2">Pembayaran Berhasil!</h1>
            <p class="text-[#a8b3ff] mb-6">Terima kasih telah menggunakan <b>WADAH</b>.</p>

            {{-- DETAIL: grid 2 kolom sejajar & nowrap --}}
            <div class="text-left bg-[#0b1638]/60 border border-[#22306b] rounded-2xl p-5 mb-6 space-y-2">
                {{-- <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-start">
          <span class="text-[#a8b3ff] whitespace-nowrap">Order ID</span>
          <span class="font-semibold text-primary break-all overflow-x-auto no-scrollbar block">
            {{ $order_id ?? '—' }}
          </span>
        </div> --}}

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Kode Transaksi</span>
                    <span class="font-semibold whitespace-nowrap">
                        {{ $trx->code_transactions ?? '—' }}
                    </span>
                </div>

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Volume</span>
                    <span class="font-semibold whitespace-nowrap">{{ $ml ?? '—' }} ml</span>
                </div>

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Minuman</span>
                    <span class="font-semibold whitespace-nowrap">{{ ucfirst(request()->query('drink', '—')) }}</span>
                </div>

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Metode Pembayaran</span>
                    <span class="font-semibold whitespace-nowrap">QRIS</span>
                </div>

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Total Pembayaran</span>
                    <span class="font-semibold whitespace-nowrap">Rp
                        {{ number_format($price ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Pembayaran Dari</span>
                    <span class="font-semibold whitespace-nowrap">
                        {{ $issuer ? strtoupper($issuer) : '—' }}
                    </span>
                </div>

                <div class="grid sm:grid-cols-[140px,1fr] gap-x-4 gap-y-1 items-center">
                    <span class="text-[#a8b3ff] whitespace-nowrap">Waktu</span>
                    <span class="font-semibold whitespace-nowrap">{{ $ts->format('d M Y, H:i:s') }}</span>
                </div>
            </div>

            {{-- INSTRUKSI PENGAMBILAN --}}
            <div class="text-left bg-[#0b1d40]/70 border border-[#2b3e7a] rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-2">
                    <div class="text-sky-400 mt-[3px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-[#cfd9ff]">
                        Air Anda sedang diproses. Silakan ambil air pada dispenser dalam
                        <b>{{ $estTime }}</b> detik.
                    </p>
                </div>
            </div>

            {{-- BUTTON PESAN LAGI --}}
            <a href="{{ route('home') }}"
                class="block w-full bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] text-[#0b1026]
                font-semibold rounded-xl py-3 hover:opacity-90 transition">
                Pesan Lagi
            </a>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-border py-4 text-center text-[#a8b3ff] mt-auto">
        © {{ date('Y') }} WADAH • UI Smart Drinking. Dibuat Tim Inovasi Teknologi Metatech.
    </footer>

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
    @include('partials.chatbot')
</body>

</html>
