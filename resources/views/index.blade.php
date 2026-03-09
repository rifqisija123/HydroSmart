<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>WADAH — Pilih Minuman</title>

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
            box-shadow: 0 10px 30px #0008, inset 0 1px 0 #ffffff07
        }

        .pill {
            border: 1px solid #22306b;
            background: #ffffff12;
            color: #cfe3ff
        }

        .drink-option {
            background: linear-gradient(180deg, #121b42, #0d1433);
            border: 1px solid #22306b
        }

        .drink-option:hover {
            transform: translateY(-2px);
            background: linear-gradient(180deg, #152254, #10183e)
        }
    </style>
</head>

<body class="min-h-screen bg-bg">

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

    <header class="sticky top-0 z-40 backdrop-blur bg-[#0b1026e6] border-b border-border">
        <div class="max-w-5xl mx-auto px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/wadahlogo.png') }}" alt="Logo Wadah" width="60">
                <div>
                    <div class="font-bold tracking-wide">Inovasi <span class="text-accent">Wadah</span></div>
                    <div class="text-sm text-[#a8b3ff]">Otomatisasi Pengisian Minuman Berbasis IoT</div>
                </div>
            </div>
            <a href="/admin/login">
                <span class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">Admin</span>
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-5 py-10">
        <div class="cardish border border-border rounded-2xl p-8 text-center">
            <div class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs mb-4">Pilih Minuman</div>
            <h1 class="text-3xl font-bold mb-2">Pilih Minuman Anda</h1>
            <p class="text-[#a8b3ff] mb-8">Silakan pilih jenis minuman yang ingin Anda isi.</p>

            <div class="grid sm:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <div class="drink-option rounded-2xl p-6 text-center">
                    <img src="{{ asset('img/coffee.png') }}" alt="Kopi" class="w-24 mx-auto mb-4">
                    <h3 class="text-xl font-bold mb-2">Kopi</h3>
                    <p class="text-[#a8b3ff] mb-4">Nikmati kopi segar dengan sistem otomatis WADAH.</p>
                    <a href="{{ route('volume', ['drink' => 'kopi']) }}"
                        class="rounded-lg px-4 py-2 text-sm pill bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] text-[#0b1026] shadow-[0_8px_24px_#4aa3ff55] font-semibold">
                        Pilih
                    </a>
                </div>

                <div class="drink-option rounded-2xl p-6 text-center">
                    <img src="{{ asset('img/teh.png') }}" alt="Teh" class="w-24 mx-auto mb-4">
                    <h3 class="text-xl font-bold mb-2">Teh Tarik</h3>
                    <p class="text-[#a8b3ff] mb-4">Hangatkan hari Anda dengan teh otomatis WADAH.</p>
                    <a href="{{ route('volume', ['drink' => 'teh']) }}"
                        class="rounded-lg px-4 py-2 text-sm pill bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] text-[#0b1026] shadow-[0_8px_24px_#4aa3ff55] font-semibold">
                        Pilih
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="border-t border-border py-4 text-center text-[#a8b3ff]">
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
