<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>WADAH — Akses Ditolak</title>

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
            background:
                radial-gradient(1200px 600px at 80% -20%, #224bd433 0%, transparent 60%),
                radial-gradient(900px 600px at 0% 0%, #00d4ff22 0%, transparent 55%),
                linear-gradient(180deg, #081022, #0b1026 60%);
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-bg flex flex-col">

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
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-5 py-12">
        <div class="cardish border border-border rounded-2xl p-8 md:p-12 max-w-lg w-full text-center">

            {{-- Animated icon --}}
            <div class="flex justify-center mb-6" style="animation: float 3s ease-in-out infinite;">
                <div
                    class="w-28 h-28 rounded-full bg-[#ff4a4a12] border border-[#ff4a4a33] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-[#ff6b6b]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
            </div>

            <div class="text-6xl font-extrabold text-[#ff6b6b] tracking-tight">403</div>
            <h1 class="text-xl font-bold mt-3">Akses Ditolak</h1>
            <p class="text-[#a8b3ff] mt-2 text-sm leading-relaxed">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Silakan kembali ke halaman utama.
            </p>

            <div class="mt-6">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 font-semibold rounded-xl px-6 py-3 text-[#0b1026]
                  bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] shadow-lg
                  hover:shadow-[0_8px_24px_#4aa3ff55] active:translate-y-[1px] transition-all">
                    ← Kembali ke Beranda
                </a>
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

</body>

</html>
