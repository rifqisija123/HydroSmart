<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>WADAH — Detail {{ ucfirst($drink) }} {{ $ml }} ml</title>

  <link rel="icon" type="image/png" href="{{ asset('img/wadahcircle.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('img/wadahcircle.png') }}">

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
       
      <img src="{{ asset('img/wadahcircle.png') }}"
           alt="Loading Wadah..."
           style="width:120px; height:120px; animation:spin 2s linear infinite;">

      <p style="color:#cfe3ff; margin-top:20px; font-size:18px; letter-spacing:1px;">
          Loading...
      </p>
  </div>

  <style>
    @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>

  <header class="sticky top-0 z-40 backdrop-blur bg-[#0b1026e6] border-b border-border">
    <div class="max-w-5xl mx-auto px-5 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('img/wadahlogo.png') }}" alt="Water Automation Dispenser Aman Hemat" width="60">
        <div>
          <div class="font-bold tracking-wide">Inovasi <span class="text-accent">Wadah</span></div>
          <div class="text-sm text-[#a8b3ff]">Detail Pilihan</div>
        </div>
      </div>
      <a href="{{ route('volume', ['drink' => $drink]) }}" class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">← Kembali</a>
    </div>
  </header>

  <main class="flex-1 max-w-5xl mx-auto px-5 py-8">
    <div class="cardish border border-border rounded-2xl p-6">
      <div class="grid md:grid-cols-2 gap-6">
        <div>
          <div class="text-sm text-[#a8b3ff]">Detail Pilihan</div>
          <div class="mt-1">
            <div class="text-4xl font-extrabold tracking-wide">
              {{ ucfirst($drink) }}
            </div>
            <div class="text-base text-[#a8b3ff] font-medium mt-2">
              Pilihan Volume:
            </div>
            <div class="text-3xl font-extrabold text-primary mt-1">
              {{ $ml }} ml
            </div>
          </div>
          <div class="mt-2 text-xl font-bold text-primary">
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
          <svg viewBox="0 0 240 260" width="230" height="260" aria-hidden="true">
            <defs>
              <linearGradient id="panelBg" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#17305c"/>
                <stop offset="100%" stop-color="#0b1026"/>
              </linearGradient>
          
              <linearGradient id="glow" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#4aa3ff" stop-opacity="0.5"/>
                <stop offset="100%" stop-color="#0088ff" stop-opacity="0.2"/>
              </linearGradient>
          
              <linearGradient id="cupShine" x1="0" x2="1" y1="0" y2="0">
                <stop offset="0%" stop-color="#ffffff33"/>
                <stop offset="100%" stop-color="#ffffff00"/>
              </linearGradient>
          
            </defs>
          
            <rect x="10" y="10" width="220" height="240" rx="18" fill="url(#panelBg)"
                  stroke="#2f4d8a" stroke-width="2" />
          
            <rect x="10" y="10" width="220" height="240" rx="18" fill="url(#glow)" opacity="0.25" />
          
            <rect x="20" y="20" width="200" height="45" rx="8"
                  fill="#0f1c3d" stroke="#3b6dd6" stroke-opacity="0.6" />
          
            <text x="120" y="48" text-anchor="middle"
                  fill="#cfe3ff" font-size="12" font-weight="600">
                Simulasi Dispenser
            </text>
          
            <rect x="30" y="75" width="180" height="140" rx="12"
                  fill="#ffffff08" stroke="#395fb0" stroke-opacity="0.4"/>
          
            <rect x="30" y="200" width="180" height="20" rx="6"
                  fill="#1b2d55" stroke="#508cff" stroke-width="1.2" />
          
            <rect x="105" y="130" width="40" height="50" rx="6"
                  fill="#b8dcff22" stroke="#65b6ff" />
          
            <rect x="105" y="130" width="12" height="50" rx="6"
                  fill="url(#cupShine)" />
          
            <rect x="135" y="95" width="4" height="20" fill="#66c8ff" />
          
            <circle cx="137" cy="122" r="4" fill="#00d4ff" opacity="0.8">
              <animate attributeName="cy" from="122" to="140" dur="0.9s" repeatCount="indefinite" />
              <animate attributeName="opacity" from="0.9" to="0" dur="0.9s" repeatCount="indefinite" />
            </circle>
          
            <!-- Coffee icon (left decoration) -->
            <text x="50" y="120" font-size="28" opacity="0.25">☕</text>
          
            <!-- Tea icon (right decoration) -->
            <text x="170" y="120" font-size="28" opacity="0.25">🍃</text>
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
        const res  = await fetch('{{ route('pay', ['drink' => $drink]) }}', {
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
          onSuccess: function(result) {
            console.log("Success", result);
            handleRedirect(result);
          },
          onPending: function(result) {
            console.log("Pending", result);
            // Jangan redirect ke success di sini! cukup tampilkan notifikasi
            showErr('Pembayaran masih menunggu, silakan selesaikan di aplikasi pembayaran Anda.');
          },
          onError: function(result) {
            console.error("Error", result);
            showErr('Terjadi kesalahan saat proses pembayaran.');
          },
          onClose: function() {
            console.warn('QRIS ditutup tanpa pembayaran');
            const params = new URLSearchParams({
              drink: "{{ $drink }}",
              ml: String({{ $ml }})
            });
            window.location.href = `/close?${params.toString()}`;
          }
        });

        function handleRedirect(r) {
          const ts = r.settlement_time || r.transaction_time || new Date().toISOString();
          const params = new URLSearchParams({
              order_id: r.order_id,
              ml: String({{ $ml }}),
              price: String({{ $price }}),
              estTime: String({{ $estTime }}),
              ts,
              drink: "{{ $drink }}",
          });
          window.location.href = `/success?${params.toString()}`;
        }
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
