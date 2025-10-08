<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hydro Smart — Otomatisasi Pengisian Air Minum Berbasis IoT</title>

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
    .ml-option{background:linear-gradient(180deg,#121b42,#0d1433);border:1px solid #22306b}
    .ml-option:hover{transform:translateY(-2px);background:linear-gradient(180deg,#152254,#10183e)}
    .bottle-wrapper{
      display:flex;
      justify-content:center;
      margin-top:20px;
    }
    .bottle{
      width:160px;
      height:240px;
      border-radius:24px;
      background:#ffffff08;
      border:1px solid #ffffff18;
      backdrop-filter:blur(2px);
      position:relative;
    }
    .bottle::before{
      content:"";
      position:absolute;
      left:18px; top:12px;
      width:60%; height:12px;
      border-radius:10px;
      background:#fff2;
    }
    .water{
      --level:50%;
      position:absolute;
      bottom:0; left:0; right:0;
      height:var(--level);
      background:linear-gradient(180deg,#71d8ff,#00b1ff 60%,#0088ff);
      border-bottom-left-radius:24px;
      border-bottom-right-radius:24px;
      overflow:hidden;
    }
    .wave{
      position:absolute;
      left:-50%; top:-120%;
      width:200%; height:200%;
      opacity:.35;
      background:radial-gradient(50% 50% at 50% 50%,#fff 0 30%,transparent 31% 100%);
      animation:wave 6s linear infinite;
      mask:radial-gradient(55% 55% at 50% 50%,#000 0 30%,transparent 31% 100%);
    }
    .wave.two{ animation-duration:9s; opacity:.25; }
    @keyframes wave{ to{ transform:translateX(-25%) rotate(360deg);} }
  </style>
</head>
<body class="min-h-screen bg-bg">
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
          <div class="text-sm text-[#a8b3ff]">Otomatisasi Pengisian Air Minum Berbasis IoT</div>
        </div>
      </div>
      <span class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">Beranda</span>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-5 py-8">
    <div class="grid md:grid-cols-2 gap-5">
      <section class="cardish border border-border rounded-2xl p-6">
        <div class="inline-flex pill rounded-full px-3 py-2 text-xs mb-3">Pilih Volume</div>
        <h1 class="text-2xl md:text-3xl font-bold">Pilih Volume Air</h1>
        <p class="text-[#a8b3ff] mt-2">Sentuh salah satu ukuran untuk melihat detail & harga.</p>
        <div class="bottle-wrapper">
          <div class="bottle">
            <div class="water" style="--level:50%">
              <div class="wave"></div>
              <div class="wave two"></div>
            </div>
          </div>
        </div>                
      </section>

      <section class="cardish border border-border rounded-2xl p-6 flex flex-col gap-4">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold">Volume Tersedia</h3>
          <span class="text-[#a8b3ff] text-sm">Klik “Pilih” untuk lanjut</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 gap-3">
          @foreach(collect($pricing)->keys()->sort() as $ml)
            @php $price = $pricing[$ml]; @endphp
            <div class="ml-option rounded-2xl p-4">
              <div class="flex items-center justify-between gap-2">
                <div>
                  <div class="text-xl font-bold">{{ $ml }} ml</div>
                  <div class="text-[#b7c7ff] font-semibold">
                    Rp {{ number_format($price,0,',','.') }}
                  </div>
                </div>
                <a href="{{ route('detail', ['ml'=>$ml]) }}"
                   class="rounded-lg px-3 py-2 text-sm pill bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] text-[#0b1026] shadow-[0_8px_24px_#4aa3ff55] font-semibold">
                  Pilih
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    </div>
  </main>

  <footer class="border-t border-border py-4 text-center text-[#a8b3ff]">
    © {{ date('Y') }} Hydro Smart • UI Smart Drinking. Dibuat Tim Inovasi Teknologi Metatech.
  </footer>
</body>
</html>
