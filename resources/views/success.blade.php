<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Berhasil — WADAH</title>

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
    body{
      color:#e8ecff;
      background:
        radial-gradient(1200px 600px at 80% -20%, #224bd433 0%, transparent 60%),
        radial-gradient(900px 600px at 0% 0%, #00d4ff22 0%, transparent 55%),
        linear-gradient(180deg, #081022, #0b1026 60%);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }
    .cardish{
      background:linear-gradient(180deg,#0f1633,#0b1026);
      box-shadow:0 10px 30px #0008, inset 0 1px 0 #ffffff07;
    }
    .pill{
      border:1px solid #22306b;
      background:#ffffff12;
      color:#cfe3ff;
    }
    /* Hilangkan scrollbar untuk area yang bisa digeser horizontal */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Animasi ceklist: pop in lalu tenang, ulang tiap 3s */
    @keyframes popPulse {
      0%   { transform: scale(0.6); opacity: 0; filter: drop-shadow(0 0 0 rgba(34,197,94,0)); }
      10%  { transform: scale(1.15); opacity: 1; filter: drop-shadow(0 8px 24px rgba(34,197,94,.45)); }
      18%  { transform: scale(1.0);  opacity: 1; }
      70%  { transform: scale(1.0);  opacity: 1; }
      100% { transform: scale(0.98); opacity: 1; filter: drop-shadow(0 4px 12px rgba(34,197,94,.25)); }
    }
    .animate-pop-3s {
      animation: popPulse 3s ease-out infinite;
      transform-origin: center;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-bg">

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
      <div class="mx-auto w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-4 animate-pop-3s">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <h1 class="text-2xl font-bold mb-2">Pembayaran Berhasil!</h1>
      <p class="text-[#a8b3ff] mb-6">Terima kasih telah menggunakan <b>WADAH</b>.</p>

      {{-- DETAIL: grid 2 kolom sejajar & nowrap --}}
      <div class="text-left bg-[#0b1638]/60 border border-[#22306b] rounded-2xl p-5 mb-6">
        <div class="grid grid-cols-[150px,1fr] gap-x-4 items-start py-1">
          <span class="text-[#a8b3ff] whitespace-nowrap">Kode Transaksi</span>
          <span class="font-semibold text-primary break-all whitespace-normal">
            {{ $order_id ?? '—' }}
          </span>
        </div>        
        <div class="grid grid-cols-[150px,1fr] gap-x-4 items-center py-1">
          <span class="text-[#a8b3ff] whitespace-nowrap">Volume</span>
          <span class="font-semibold whitespace-nowrap overflow-x-auto no-scrollbar">{{ $ml ?? '—' }} ml</span>
        </div>
        <div class="grid grid-cols-[150px,1fr] gap-x-4 items-center py-1">
          <span class="text-[#a8b3ff] whitespace-nowrap">Metode Pembayaran</span>
          <span class="font-semibold whitespace-nowrap">QRIS</span>
        </div>
        <div class="grid grid-cols-[150px,1fr] gap-x-4 items-center py-1">
          <span class="text-[#a8b3ff] whitespace-nowrap">Total Pembayaran</span>
          <span class="font-semibold whitespace-nowrap">Rp {{ number_format($price ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="grid grid-cols-[150px,1fr] gap-x-4 items-center py-1">
          <span class="text-[#a8b3ff] whitespace-nowrap">Waktu</span>
          <span class="font-semibold whitespace-nowrap">{{ $ts->format('d M Y, H:i:s') }}</span>
        </div>
      </div>

      {{-- INSTRUKSI PENGAMBILAN --}}
      <div class="text-left bg-[#0b1d40]/70 border border-[#2b3e7a] rounded-2xl p-4 mb-6">
        <div class="flex items-start gap-2">
          <div class="text-sky-400 mt-[3px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-sm text-[#cfd9ff]">
            Air Anda sedang diproses. Silakan ambil air pada dispenser dalam
            <b>{{ $estTime ?? 5 }}</b> detik.
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

</body>
</html>
