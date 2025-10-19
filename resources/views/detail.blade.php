<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>WADAH — Detail Pilihan {{ $ml }} ml</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        colors: { bg:"#0b1026", card:"#111735", border:"#22306b", primary:"#4aa3ff", accent:"#00d4ff", text:"#e8ecff" }
      }}
    }
  </script>
  <style>
    body{
      color:#e8ecff;
      background:
        radial-gradient(1200px 600px at 80% -20%, #224bd433 0%, transparent 60%),
        radial-gradient(900px 600px at 0% 0%, #00d4ff22 0%, transparent 55%),
        linear-gradient(180deg, #081022, #0b1026 60%);
    }
    .cardish{background:linear-gradient(180deg,#0f1633,#0b1026);box-shadow:0 10px 30px #0008, inset 0 1px 0 #ffffff07}
    .pill{border:1px solid #22306b;background:#ffffff12;color:#cfe3ff}
  </style>

  {{-- Snap JS production --}}
  <script src="https://app.midtrans.com/snap/snap.js"
          data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="min-h-screen bg-bg flex flex-col">
  <header class="sticky top-0 z-40 backdrop-blur bg-[#0b1026e6] border-b border-border">
    <div class="max-w-5xl mx-auto px-5 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('img/wadahlogo.png') }}" alt="Water Automation Dispenser Aman Hemat" width="60">
        <div>
          <div class="font-bold tracking-wide">Inovasi <span class="text-accent">Wadah</span></div>
          <div class="text-sm text-[#a8b3ff]">Detail Pilihan</div>
        </div>
      </div>
      <a href="{{ route('home') }}" class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">← Kembali</a>
    </div>
  </header>

  <main class="flex-1 max-w-5xl mx-auto px-5 py-8">
    <div class="cardish border border-border rounded-2xl p-6">
      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <div class="text-sm text-[#a8b3ff]">Detail Pilihan</div>
          <div class="text-4xl font-extrabold mt-1">{{ $ml }} ml</div>
          <div class="mt-2 text-2xl font-bold text-primary">
            Rp {{ number_format($price,0,',','.') }}
          </div>
          <div class="text-[#a8b3ff] mt-2">
            Perkiraan waktu pengisian: <b>{{ $estTime }}</b> detik
          </div>

          <div class="mt-5 flex flex-wrap gap-3">
            <button id="btnPay"
                    class="font-semibold rounded-xl px-5 py-3 text-[#0b1026]
                           bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] shadow-lg active:translate-y-[1px]">
              Lanjutkan Pengisian
            </button>
            <a href="{{ route('home') }}" class="pill rounded-xl px-4 py-3">Ubah Pilihan</a>
          </div>

          <div id="uiError"
               class="hidden mt-4 border border-amber-400 rounded-xl px-3 py-2 text-amber-100 bg-amber-900/30 text-sm">
          </div>
        </div>

        <div class="grid place-items-center">
          <svg viewBox="0 0 180 180" width="200" height="200" aria-hidden="true">
            <defs>
              <linearGradient id="lg" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#71d8ff"/>
                <stop offset="100%" stop-color="#0088ff"/>
              </linearGradient>
            </defs>
            <rect rx="20" ry="20" x="10" y="10" width="160" height="160" fill="url(#lg)" opacity=".25" stroke="#3a5fff" stroke-opacity=".5"/>
            <path d="M60 35 h60 a12 12 0 0 1 12 12 v86 a12 12 0 0 1 -12 12 h-60 a12 12 0 0 1 -12 -12 v-86 a12 12 0 0 1 12 -12 z" fill="#0b1026" stroke="#4aa3ff"/>
            <rect x="66" y="50" width="48" height="60" rx="6" fill="#071024" stroke="#2349ff"/>
            <rect x="76" y="58" width="28" height="44" fill="#00b1ff" opacity=".5"/>
            <circle cx="90" cy="130" r="6" fill="#00c389"/>
            <text x="90" y="155" text-anchor="middle" font-size="12" fill="#9cc3ff">Simulasi Dispenser</text>
          </svg>
        </div>
      </div>
    </div>
  </main>

  <footer class="border-t border-border py-4 text-center text-[#a8b3ff]">
    © {{ date('Y') }} WADAH • UI Smart Drinking. Dibuat Tim Inovasi Teknologi Metatech.
  </footer>

  <script>
    const rp = v => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(v);
    const $  = q => document.querySelector(q);

    document.getElementById('btnPay').addEventListener('click', async ()=>{
      try{
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res  = await fetch('{{ route('pay') }}', {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf},
          body: JSON.stringify({ ml: {{ $ml }} })
        });
        if(!res.ok){
          const txt = await res.text();
          throw new Error(`Gagal ambil token: ${res.status} ${txt}`);
        }
        const { token } = await res.json();

        // Snap QRIS
        window.snap.pay(token, {
            onSuccess: (r)=>{
                const ts =
                    r.settlement_time ||
                    r.transaction_time ||
                    new Date().toISOString();

                const params = new URLSearchParams({
                    order_id: r.order_id,
                    ml: String({{ $ml }}),
                    price: String({{ $price }}),
                    estTime: String({{ $estTime }}),
                    ts,
                });

                window.location.href = `/success?${params.toString()}`;
            },
          onPending: (r)=> console.log('pending', r),
          onError:   (r)=> { console.error('error', r); showErr('Pembayaran gagal. Coba lagi.'); },
          onClose:   ()=> console.warn('closed')
        });
      }catch(e){
        console.error(e);
        showErr(e.message || 'Terjadi kesalahan jaringan.');
      }
    });

    function showErr(msg){
      const box = $('#uiError');
      box.textContent = msg;
      box.classList.remove('hidden');
    }
  </script>
</body>
</html>
