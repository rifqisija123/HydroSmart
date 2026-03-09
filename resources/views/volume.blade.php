<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>WADAH — Pilih Volume {{ ucfirst($drink) }}</title>

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

    @php
        $isCoffee = $drink === 'kopi';
        // Coffee: warm browns   |   Tea: golden amber
        $liquidTop = $isCoffee ? '#8B6F47' : '#c9a23a';
        $liquidMid = $isCoffee ? '#5C3D1E' : '#b8860b';
        $liquidBottom = $isCoffee ? '#3E2410' : '#8B6914';
        $glowColor = $isCoffee ? '#8B6F4733' : '#c9a23a33';
        $drinkEmoji = $isCoffee ? '☕' : '🍵';
        $drinkLabel = $isCoffee ? 'Kopi' : 'Teh Tarik';
    @endphp

    <style>
        body {
            color: #e8ecff;
            background: #0b1026;
            background-image:
                radial-gradient(at 80% 0%, hsla(227, 72%, 48%, 0.25) 0px, transparent 50%),
                radial-gradient(at 0% 100%, {{ $glowColor }} 0px, transparent 50%),
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

        /* Volume option cards */
        .ml-option {
            background: linear-gradient(180deg, #121b42, #0d1433);
            border: 1px solid #22306b;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .ml-option:hover {
            transform: translateY(-3px);
            background: linear-gradient(180deg, #152254, #10183e);
            box-shadow: 0 8px 24px {{ $glowColor }};
        }

        /* Bottle / cup */
        .cup-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            position: relative;
        }

        .cup {
            width: 120px;
            height: 160px;
            border-radius: 0 0 20px 20px;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            background: #ffffff08;
            border: 1.5px solid #ffffff20;
            position: relative;
            overflow: hidden;
        }

        .cup::before {
            content: "";
            position: absolute;
            left: 10px;
            top: 6px;
            width: 50%;
            height: 8px;
            border-radius: 8px;
            background: #fff1;
            z-index: 2;
        }

        /* Handle for cup */
        .cup-handle {
            position: absolute;
            right: -22px;
            top: 20px;
            width: 22px;
            height: 50px;
            border: 2px solid #ffffff20;
            border-left: none;
            border-radius: 0 14px 14px 0;
        }

        .liquid {
            --level: 55%;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--level);
            background: linear-gradient(180deg, {{ $liquidTop }}, {{ $liquidMid }} 60%, {{ $liquidBottom }});
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 18px;
            overflow: hidden;
            transition: height 0.6s ease;
        }

        /* Steam animation */
        .steam {
            position: absolute;
            width: 6px;
            background: linear-gradient(180deg, transparent, #ffffff22);
            border-radius: 10px;
            animation: rise 2s ease-in infinite;
            opacity: 0;
        }

        .steam:nth-child(1) {
            left: 30%;
            height: 20px;
            animation-delay: 0s;
        }

        .steam:nth-child(2) {
            left: 50%;
            height: 16px;
            animation-delay: 0.6s;
        }

        .steam:nth-child(3) {
            left: 68%;
            height: 22px;
            animation-delay: 1.2s;
        }

        @keyframes rise {
            0% {
                transform: translateY(0);
                opacity: 0;
            }

            30% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-40px);
                opacity: 0;
            }
        }

        /* Wave effect inside liquid */
        .wave {
            position: absolute;
            left: -50%;
            top: -120%;
            width: 200%;
            height: 200%;
            opacity: .3;
            background: radial-gradient(50% 50% at 50% 50%, #fff 0 30%, transparent 31% 100%);
            animation: wave 6s linear infinite;
            mask: radial-gradient(55% 55% at 50% 50%, #000 0 30%, transparent 31% 100%);
        }

        .wave.two {
            animation-duration: 9s;
            opacity: .2;
        }

        @keyframes wave {
            to {
                transform: translateX(-25%) rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
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

    <header class="sticky top-0 z-40 backdrop-blur bg-[#0b1026e6] border-b border-border">
        <div class="max-w-5xl mx-auto px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/wadahlogo.png') }}" alt="Water Automation Dispenser Aman Hemat" width="60">
                <div>
                    <div class="font-bold tracking-wide">Inovasi <span class="text-accent">Wadah</span></div>
                    <div class="text-sm text-[#a8b3ff]">Otomatisasi Pengisian Minuman Berbasis IoT</div>
                </div>
            </div>
            <a href="{{ route('home') }}" class="pill inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs">←
                Kembali</a>
        </div>
    </header>

    <main class="flex-1 max-w-3xl mx-auto px-5 py-8 w-full">
        <section class="cardish border border-border rounded-2xl p-6 md:p-8">

            {{-- Top: Drink info + cup illustration --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-6 mb-6 pb-6 border-b border-border">
                {{-- Drink info --}}
                <div class="flex-1">
                    <div class="inline-flex pill rounded-full px-3 py-1.5 text-xs mb-3">{{ $drinkEmoji }}
                        {{ $drinkLabel }}</div>
                    <h1 class="text-2xl md:text-3xl font-bold">Pilih Volume</h1>
                    <p class="text-[#a8b3ff] mt-2 text-sm">Pilih ukuran yang Anda inginkan untuk
                        <b>{{ $drinkLabel }}</b>.
                    </p>
                </div>

                {{-- Cup illustration --}}
                <div class="cup-wrapper shrink-0 self-center" style="height:180px;">
                    {{-- Steam --}}
                    <div style="position:relative;">
                        <div class="steam"></div>
                        <div class="steam"></div>
                        <div class="steam"></div>
                        <div class="cup">
                            <div class="liquid" id="liquidEl">
                                <div class="wave"></div>
                                <div class="wave two"></div>
                            </div>
                            <div class="cup-handle"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Volume options --}}
            @if (empty($pricing))
                <div class="rounded-2xl border border-amber-400/40 bg-amber-500/10 p-4">
                    <div class="text-amber-300 font-semibold">Volume Tidak Tersedia!</div>
                    <div class="text-amber-200/90 text-sm mt-1">
                        Volume untuk {{ ucfirst($drink) }} belum tersedia. Silakan hubungi admin!
                    </div>
                    <a href="{{ route('home') }}" class="inline-block mt-3 px-3 py-2 rounded-lg pill">
                        ← Kembali
                    </a>
                </div>
            @else
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">Volume Tersedia</h3>
                    <span class="text-[#a8b3ff] text-xs">Klik untuk memilih</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach (collect($pricing)->keys()->sort() as $ml)
                        @php $price = $pricing[$ml]; @endphp
                        <a href="{{ route('detail', ['ml' => $ml, 'drink' => $drink]) }}"
                            class="ml-option rounded-2xl p-4 block group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0"
                                    style="background:{{ $glowColor }}; border:1px solid {{ $liquidTop }}44;">
                                    💧
                                </div>
                                <div class="flex-1">
                                    <div class="text-lg font-bold">{{ $ml }} ml</div>
                                    <div class="text-[#b7c7ff] text-sm font-semibold">
                                        Rp {{ number_format($price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div
                                    class="w-8 h-8 rounded-lg bg-gradient-to-b from-[#58b1ff] to-[#4aa3ff] flex items-center justify-center
                            text-[#0b1026] text-sm font-bold opacity-70 group-hover:opacity-100 transition-opacity">
                                    →
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </section>
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
