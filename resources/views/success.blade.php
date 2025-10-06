<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Berhasil — Hydro Smart</title>

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
  </style>
</head>
<body class="min-h-screen flex flex-col bg-bg">

  {{-- HEADER --}}
  <header class="sticky top-0 z-40 backdrop-blur bg-[#0b1026e6] border-b border-border">
    <div class="max-w-5xl mx-auto px-5 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl grid place-items-center shadow-inner"
             style="background:radial-gradient(circle at 70% 30%, #00d4ff, #4aa3ff)">
             <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
              </svg>
        </div>
        <div>
          <div class="font-bold tracking-wide">Hydro <span class="text-accent">Smart</span></div>
          <div class="text-sm text-[#a8b3ff]">Pembayaran Success</div>
        </div>
      </div>
      <a href="{{ route('home') }}">
        <span class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">Beranda</span>
      </a>
    </div>
  </header>

  {{-- MAIN CONTENT --}}
  <main class="flex-grow max-w-md w-full mx-auto px-6 py-12">
    <div class="cardish border border-border rounded-2xl p-8 text-center">

      {{-- Icon Success --}}
      <div class="mx-auto w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <h1 class="text-2xl font-bold mb-2">Pembayaran Berhasil!</h1>
      <p class="text-[#a8b3ff] mb-6">Terima kasih telah menggunakan <b>Hydro Smart</b>.</p>

      {{-- CARD DETAIL PEMBAYARAN --}}
      <div class="text-left bg-[#0b1638]/60 border border-[#22306b] rounded-2xl p-5 mb-6">
        <div class="flex justify-between py-1">
          <span class="text-[#a8b3ff]">Kode Transaksi</span>
          <span class="font-semibold text-primary">{{ $order_id ?? '—' }}</span>
        </div>
        <div class="flex justify-between py-1">
          <span class="text-[#a8b3ff]">Volume</span>
          <span class="font-semibold">{{ $ml ?? '—' }} ml</span>
        </div>
        <div class="flex justify-between py-1">
          <span class="text-[#a8b3ff]">Metode Pembayaran</span>
          <span class="font-semibold">QRIS</span>
        </div>
        <div class="flex justify-between py-1">
          <span class="text-[#a8b3ff]">Total Pembayaran</span>
          <span class="font-semibold">Rp {{ number_format($price ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between py-1">
            <span class="text-[#a8b3ff]">Waktu</span>
            <span class="font-semibold">
              {{ $ts->format('d M Y, H:i:s') }}
            </span>
        </div>          
      </div>

      {{-- INSTRUKSI PENGAMBILAN --}}
      <div class="text-left bg-[#0b1d40]/70 border border-[#2b3e7a] rounded-2xl p-4 mb-6">
        <div class="flex items-start gap-2">
          <div class="text-sky-400 mt-[3px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
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
    © {{ date('Y') }} Hydro Smart • UI Smart Drinking. Dibuat Tim Inovasi Teknologi Metatech.
  </footer>

</body>
</html>
