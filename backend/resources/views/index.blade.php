<!DOCTYPE html>
<html lang="fil">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Andaya's Native Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            cream: '#f5f0e8',
            'cream-dark': '#ede8dc',
            bark: '#2c1a0e',
            'bark-mid': '#4a2e1a',
            rust: '#8b3a1a',
            gold: '#b8924a',
            'gold-light': '#d4aa6a',
            sage: '#6b7c5a',
          },
          fontFamily: {
            display: ['Cormorant Garamond', 'Georgia', 'serif'],
            sans: ['Jost', 'sans-serif'],
          },
        }
      }
    }
  </script>
  <style>
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.06'/%3E%3C/svg%3E");
      pointer-events: none;
      z-index: 50;
      opacity: 0.4;
    }
    html { scroll-behavior: smooth; }

    .brand-italic {
      background: linear-gradient(135deg, #8b3a1a 0%, #b8924a 50%, #8b3a1a 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .divider-ornament::before,
    .divider-ornament::after {
      content: '';
      display: inline-block;
      width: 40px;
      height: 1px;
      background: linear-gradient(90deg, transparent, #b8924a);
      vertical-align: middle;
      margin: 0 10px;
    }
    .divider-ornament::after { background: linear-gradient(90deg, #b8924a, transparent); }

    .btn-primary { position: relative; overflow: hidden; }
    .btn-primary::after {
      content: '';
      position: absolute;
      top: 0; left: -100%;
      width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
      transition: left 0.5s ease;
    }
    .btn-primary:hover::after { left: 100%; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }
    .anim-1 { animation: fadeIn 0.6s ease forwards; }
    .anim-2 { animation: fadeUp 0.7s ease 0.15s both; }
    .anim-3 { animation: fadeUp 0.7s ease 0.30s both; }
    .anim-4 { animation: fadeUp 0.7s ease 0.45s both; }
    .anim-5 { animation: fadeUp 0.7s ease 0.60s both; }

    .nav-link { position: relative; }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -2px; left: 50%;
      width: 0; height: 1px;
      background: #b8924a;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }
    .nav-link:hover::after { width: 100%; }
    .nav-link.active-section::after { width: 100%; }

    .logo-ring { border: 1.5px solid rgba(184, 146, 74, 0.4); }

    .value-card, .service-card {
      background: linear-gradient(135deg, rgba(245,240,232,0.8) 0%, rgba(237,228,208,0.6) 100%);
      border: 1px solid rgba(44, 26, 14, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .value-card:hover, .service-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(44,26,14,0.1);
    }

    /* Scroll reveal */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body class="bg-cream font-sans">

  <!-- NAVBAR -->
  <nav class="anim-1 fixed top-0 left-0 right-0 z-40 bg-bark/95 backdrop-blur-sm px-6 md:px-10 h-16 flex items-center justify-between shadow-sm">
    <a href="#hero" class="flex items-center gap-3 group">
      <img src="{{ asset('images/andayalogo.png') }}" alt="Andaya Logo" class="h-14 w-auto object-contain">
      <div class="leading-tight">
        <div class="text-gold font-display text-base font-semibold tracking-widest uppercase">Andaya's</div>
        <div class="text-gold-light/60 text-[10px] tracking-[0.2em] uppercase font-sans font-light">Native Products</div>
      </div>
    </a>

    <div class="flex items-center gap-7">
      <a href="#hero" data-section="hero" class="nav-link text-sm tracking-[0.15em] uppercase font-light transition-colors duration-200 text-cream/70 hover:text-cream">Home</a>
      <a href="#about" data-section="about" class="nav-link text-sm tracking-[0.15em] uppercase font-light transition-colors duration-200 text-cream/70 hover:text-cream">About Us</a>
      <a href="#services" data-section="services" class="nav-link text-sm tracking-[0.15em] uppercase font-light transition-colors duration-200 text-cream/70 hover:text-cream">Services</a>
      <a href="{{ route('login') }}" class="ml-2 px-5 py-2 border border-gold/50 text-gold text-sm tracking-[0.15em] uppercase font-light hover:bg-gold hover:text-bark transition-all duration-300 rounded-sm">Sign In</a>
      <a href="{{ route('register') }}" class="ml-1 px-5 py-2 bg-gold text-bark text-sm tracking-[0.15em] uppercase font-medium hover:bg-gold-light transition-all duration-300 rounded-sm">Register</a>
    </div>
  </nav>

  <style>
    .hero-section-bg {
      background:
        radial-gradient(ellipse at 10% 60%, rgba(139,58,26,0.07) 0%, transparent 55%),
        radial-gradient(ellipse at 90% 20%, rgba(184,146,74,0.10) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 100%, rgba(107,124,90,0.06) 0%, transparent 60%),
        linear-gradient(160deg, #f5f0e8 0%, #ede8dc 60%, #e8dfc8 100%);
    }
    .hero-visual {
      background: linear-gradient(145deg, #ede4d0 0%, #e0d4b8 100%);
      border: 1px solid rgba(184,146,74,0.2);
    }
    .float-badge {
      animation: floatY 3s ease-in-out infinite;
    }
    .float-badge-2 {
      animation: floatY 3.5s ease-in-out 0.5s infinite;
    }
    .float-badge-3 {
      animation: floatY 4s ease-in-out 1s infinite;
    }
    @keyframes floatY {
      0%, 100% { transform: translateY(0px); }
      50%       { transform: translateY(-8px); }
    }
    .gold-ring {
      border: 1.5px solid rgba(184,146,74,0.35);
    }
    .stat-pill {
      background: rgba(44,26,14,0.06);
      border: 1px solid rgba(184,146,74,0.25);
      backdrop-filter: blur(4px);
    }
  </style>

  <!-- ───── HERO SECTION ───── -->
  <section id="hero" class="hero-section-bg h-screen flex items-center justify-center px-6 md:px-12 pt-16 pb-6 overflow-hidden">

    <!-- bg decorative circles -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
      <div class="absolute -top-20 -right-20 w-[420px] h-[420px] rounded-full bg-gold/8 blur-3xl"></div>
      <div class="absolute bottom-0 -left-20 w-[350px] h-[350px] rounded-full bg-rust/6 blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full bg-sage/4 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-6xl mx-auto flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

      <!-- LEFT: Text Content -->
      <div class="flex-1 flex flex-col items-start">

        <div class="anim-2 flex items-center gap-3 mb-7">
          <div class="h-px w-8 bg-gold/60"></div>
          <span class="text-gold text-xs tracking-[0.35em] uppercase font-sans font-medium">Mga Likhain ng Pilipinas</span>
        </div>

        <div class="anim-3 mb-4">
          <h1 class="font-display text-6xl md:text-7xl lg:text-8xl font-light text-bark leading-[1.0] tracking-tight">Andaya's</h1>
          <h1 class="font-display text-6xl md:text-7xl lg:text-8xl font-light italic brand-italic leading-[1.0] tracking-tight">Native</h1>
          <h1 class="font-display text-6xl md:text-7xl lg:text-8xl font-light text-bark leading-[1.0] tracking-tight">Products</h1>
        </div>

        <div class="anim-3 flex items-center gap-3 my-4">
          <div class="h-px w-12 bg-gradient-to-r from-gold to-transparent"></div>
          <div class="w-1.5 h-1.5 rounded-full bg-gold/60"></div>
          <div class="h-px w-12 bg-gradient-to-l from-gold to-transparent"></div>
        </div>

        <p class="anim-4 text-bark-mid/75 font-sans text-sm leading-relaxed font-light max-w-md mb-5">
          Tunay na likhain ng Pilipino — gawa sa puso, para sa pamilya. Sariwang-sariwa, diretso sa inyong pintuan.
        </p>

        <div class="anim-5 flex flex-col sm:flex-row gap-3">
          <a href="{{ route('register') }}" class="btn-primary flex items-center justify-center gap-2.5 bg-bark text-cream text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:bg-bark-mid transition-colors duration-300 shadow-lg shadow-bark/25">
            Mag-order Na
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </a>
          <a href="#featured" class="flex items-center justify-center gap-2 border border-bark/25 text-bark text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:border-gold hover:text-rust transition-all duration-300">
            Tingnan ang Produkto
          </a>
        </div>
      </div>

      <!-- RIGHT: Visual Card -->
      <div class="anim-3 flex-1 flex items-center justify-center w-full max-w-sm lg:max-w-md relative">

        <!-- Main visual card -->
        <div class="hero-visual relative w-full rounded-2xl p-8 shadow-2xl shadow-bark/20 overflow-hidden">
          <!-- corner accents -->
          <div class="absolute top-4 left-4 w-6 h-6 border-t-2 border-l-2 border-gold/50"></div>
          <div class="absolute top-4 right-4 w-6 h-6 border-t-2 border-r-2 border-gold/50"></div>
          <div class="absolute bottom-4 left-4 w-6 h-6 border-b-2 border-l-2 border-gold/50"></div>
          <div class="absolute bottom-4 right-4 w-6 h-6 border-b-2 border-r-2 border-gold/50"></div>

          <!-- center emblem -->
          <div class="flex flex-col items-center justify-center py-10">
            <div class="gold-ring w-28 h-28 rounded-full flex items-center justify-center bg-cream/80 shadow-inner mb-5">
              <div class="gold-ring w-20 h-20 rounded-full flex items-center justify-center bg-cream">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none">
                  <path d="M12 2C6 2 3 7 3 12c0 6 5 10 9 10 1-3 1-7-1-10 3 2 5 6 5 10 4-2 5-6 5-10C21 7 18 2 12 2z" fill="#b8924a" opacity="0.85"/>
                  <path d="M12 12 Q12 7 12 2" stroke="#d4aa6a" stroke-width="0.8" opacity="0.7"/>
                </svg>
              </div>
            </div>
            <p class="font-display text-2xl text-bark font-medium tracking-wide mb-1">Andaya's</p>
            <p class="text-gold text-xs tracking-[0.3em] uppercase font-light">Native Products</p>
            <div class="flex items-center gap-2 mt-4">
              <div class="h-px w-10 bg-gradient-to-r from-transparent to-gold/60"></div>
              <div class="w-1 h-1 rounded-full bg-gold/60"></div>
              <div class="h-px w-10 bg-gradient-to-l from-transparent to-gold/60"></div>
            </div>
          </div>

          <!-- bottom tag -->
          <div class="flex justify-center">
            <span class="text-[10px] tracking-[0.25em] uppercase text-bark/40 font-light">Est. Pilipinas · Homemade · Sariwa</span>
          </div>
        </div>

        <!-- Floating badges - actual products -->
        @php $heroProducts = $featured_products->take(3); @endphp

        @if($heroProducts->count() > 0)
        <div class="float-badge absolute -top-4 -right-4 bg-bark text-cream rounded-xl px-4 py-3 shadow-lg shadow-bark/30 flex items-center gap-2">
          @if($heroProducts[0]->image)
            <img src="{{ asset('storage/'.$heroProducts[0]->image) }}" class="w-8 h-8 object-contain rounded"/>
          @else
            <span class="text-xl">📦</span>
          @endif
          <div>
            <p class="text-[10px] text-cream/50 uppercase tracking-wider">{{ $heroProducts[0]->category->name }}</p>
            <p class="text-xs font-medium text-gold">{{ $heroProducts[0]->name }}</p>
          </div>
        </div>
        @endif

        @if($heroProducts->count() > 1)
        <div class="float-badge-2 absolute -bottom-4 -left-4 bg-cream border border-gold/30 rounded-xl px-4 py-3 shadow-lg shadow-bark/15 flex items-center gap-2">
          @if($heroProducts[1]->image)
            <img src="{{ asset('storage/'.$heroProducts[1]->image) }}" class="w-8 h-8 object-contain rounded"/>
          @else
            <span class="text-xl">📦</span>
          @endif
          <div>
            <p class="text-[10px] text-bark/40 uppercase tracking-wider">{{ $heroProducts[1]->category->name }}</p>
            <p class="text-xs font-medium text-bark">{{ $heroProducts[1]->name }}</p>
          </div>
        </div>
        @endif

        @if($heroProducts->count() > 2)
        <div class="float-badge-3 absolute top-1/2 -left-8 -translate-y-1/2 bg-gold text-bark rounded-xl px-4 py-3 shadow-lg shadow-gold/30 flex items-center gap-2">
          @if($heroProducts[2]->image)
            <img src="{{ asset('storage/'.$heroProducts[2]->image) }}" class="w-8 h-8 object-contain rounded"/>
          @else
            <span class="text-xl">📦</span>
          @endif
          <div>
            <p class="text-[10px] text-bark/60 uppercase tracking-wider">{{ $heroProducts[2]->category->name }}</p>
            <p class="text-xs font-semibold">{{ $heroProducts[2]->name }}</p>
          </div>
        </div>
        @endif

      </div>
    </div>
  </section>

  <!-- ───── FEATURED PRODUCTS SECTION ───── -->
  <section id="featured" class="py-24 px-5 bg-cream-dark/40">
    <div class="max-w-4xl mx-auto">
      <div class="reveal flex justify-center mb-6">
        <span class="divider-ornament text-gold text-xs tracking-[0.35em] uppercase font-sans font-medium">Piling Produkto</span>
      </div>
      <div class="reveal text-center mb-2">
        <h2 class="font-display text-5xl md:text-6xl font-light text-bark leading-[1.1] tracking-tight">Featured na</h2>
        <h2 class="font-display text-5xl md:text-6xl font-light italic brand-italic leading-[1.05] tracking-tight">Mga Produkto</h2>
      </div>
      <div class="reveal flex justify-center my-7">
        <div class="w-12 h-px bg-gradient-to-r from-transparent via-gold to-transparent"></div>
      </div>
      <p class="reveal text-center text-bark-mid/70 text-sm font-light mb-12">Piling produkto na paborito ng aming mga customer — mag-register para mag-order.</p>
      <div class="reveal grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 mb-10">
        @forelse($featured_products as $product)
        <div class="value-card rounded-xl overflow-hidden flex flex-col">
          <div class="bg-white h-32 flex items-center justify-center p-3">
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-contain"/>
            @else
              <svg class="w-10 h-10 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
          </div>
          <div class="p-5 flex flex-col flex-1">
            <span class="text-[10px] tracking-[0.2em] uppercase font-medium text-gold bg-gold/10 px-2 py-0.5 rounded-sm self-start">{{ $product->category->name }}</span>
            <h3 class="font-display text-xl text-bark font-medium mt-2 mb-1">{{ $product->name }}</h3>
            <p class="text-bark-mid/60 text-xs leading-relaxed font-light mb-3 flex-1">{{ $product->description ?? 'Katutubong produkto mula sa Pilipinas.' }}</p>
            <p class="text-gold font-display text-lg font-semibold">₱{{ number_format($product->price, 2) }}</p>
          </div>
        </div>
        @empty
        <div class="col-span-4 text-center text-bark-mid/40 text-sm py-10">Wala pang mga produkto.</div>
        @endforelse
      </div>
      <div class="reveal flex justify-center">
        <a href="{{ route('register') }}" class="btn-primary flex items-center gap-2.5 bg-bark text-cream text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:bg-bark-mid transition-colors duration-300 shadow-md shadow-bark/20">
          Mag-register para Mag-order
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ───── ABOUT SECTION ───── -->
  <section id="about" class="min-h-screen flex items-center justify-center px-5 py-24">
    <div class="max-w-3xl mx-auto w-full">

      <div class="reveal flex justify-center mb-6">
        <span class="divider-ornament text-gold text-xs tracking-[0.35em] uppercase font-sans font-medium">Ang Aming Kwento</span>
      </div>

      <div class="reveal text-center mb-2">
        <h2 class="font-display text-6xl md:text-7xl font-light text-bark leading-[1.1] tracking-tight">Sino ang</h2>
        <h2 class="font-display text-6xl md:text-7xl font-light italic brand-italic leading-[1.05] tracking-tight">Andaya's?</h2>
      </div>

      <div class="reveal flex justify-center my-7">
        <div class="w-12 h-px bg-gradient-to-r from-transparent via-gold to-transparent"></div>
      </div>

      <div class="reveal text-center max-w-xl mx-auto mb-14">
        <p class="text-bark-mid/80 font-sans text-base leading-relaxed font-light">
          Ang Andaya's Native Products ay isang negosyong nakatuon sa pagpapahalaga at pagpapakalat ng mga tunay na likhain ng Pilipino. Mula sa mga produktong gawa sa natural na materyales hanggang sa mga tradisyonal na kagamitan at damit, bawat piraso ay may kwentong nagmumula sa puso ng ating kultura.
        </p>
      </div>

      <div class="reveal grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="value-card rounded-xl p-8 text-center relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="w-12 h-12 rounded-full bg-sage/15 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-1.5 4-4 6-4 9a4 4 0 008 0c0-3-2.5-5-4-9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 12v9"/></svg>
          </div>
          <h3 class="font-display text-2xl text-bark font-medium mb-3">Kalikasan</h3>
          <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Gumagamit kami ng mga natural at sustainable na materyales para sa bawat produkto.</p>
        </div>
        <div class="value-card rounded-xl p-8 text-center relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="w-12 h-12 rounded-full bg-gold/15 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <h3 class="font-display text-2xl text-bark font-medium mb-3">Komunidad</h3>
          <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Sumusuporta kami sa mga lokal na artisano at magsasaka sa buong Pilipinas.</p>
        </div>
        <div class="value-card rounded-xl p-8 text-center relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="w-12 h-12 rounded-full bg-rust/10 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
          </div>
          <h3 class="font-display text-2xl text-bark font-medium mb-3">Kalidad</h3>
          <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Bawat produkto ay dumaan sa mahigpit na pamantayan bago makarating sa inyong mga kamay.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ───── SERVICES SECTION ───── -->
  <section id="services" class="min-h-screen flex items-center justify-center px-5 py-24">
    <div class="max-w-4xl mx-auto w-full">

      <div class="reveal flex justify-center mb-6">
        <span class="divider-ornament text-gold text-xs tracking-[0.35em] uppercase font-sans font-medium">Ang Aming Alok</span>
      </div>

      <div class="reveal text-center mb-2">
        <h2 class="font-display text-6xl md:text-7xl font-light text-bark leading-[1.1] tracking-tight">Mga</h2>
        <h2 class="font-display text-6xl md:text-7xl font-light italic brand-italic leading-[1.05] tracking-tight">Serbisyo Namin</h2>
      </div>

      <div class="reveal flex justify-center my-7">
        <div class="w-12 h-px bg-gradient-to-r from-transparent via-gold to-transparent"></div>
      </div>

      <p class="reveal text-center text-bark-mid/80 font-sans text-base leading-relaxed font-light max-w-lg mx-auto mb-12">
        Nag-aalok kami ng iba't ibang serbisyo para matulungan kayo na mahanap, mag-order, at makatanggap ng mga pinakamainam na produktong Pilipino.
      </p>

      <div class="reveal grid grid-cols-1 md:grid-cols-2 gap-5 mb-14">
        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-gold/15 flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
              <h3 class="font-display text-2xl text-bark font-medium mb-2">Online Ordering</h3>
              <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Mag-order ng inyong mga paboritong produkto nang madali at mabilis sa pamamagitan ng aming sistema.</p>
            </div>
          </div>
        </div>
        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-bark/8 flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-5 h-5 text-bark-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
              <h3 class="font-display text-2xl text-bark font-medium mb-2">Inventory Management</h3>
              <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Real-time na pagsubaybay ng mga produkto para masiguro na laging available ang inyong mga kailangan.</p>
            </div>
          </div>
        </div>
        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-sage/15 flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-5 h-5 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
            </div>
            <div>
              <h3 class="font-display text-2xl text-bark font-medium mb-2">Delivery Tracking</h3>
              <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Subaybayan ang inyong mga order mula sa aming bodega hanggang sa inyong pintuan.</p>
            </div>
          </div>
        </div>
        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-rust/10 flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-5 h-5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
              <h3 class="font-display text-2xl text-bark font-medium mb-2">Sales Reports</h3>
              <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Detalyadong ulat ng mga benta at transaksyon para sa mas matalinong pamamahala ng negosyo.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="reveal flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('register') }}" class="btn-primary flex items-center justify-center gap-2.5 bg-bark text-cream text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:bg-bark-mid transition-colors duration-300 shadow-md shadow-bark/20">
          Magsimula Na
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </a>
        <a href="#about" class="flex items-center justify-center gap-2 border border-bark/25 text-bark text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:border-gold hover:text-rust transition-all duration-300">
          Alamin ang Higit Pa
        </a>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="border-t border-bark/10 bg-bark text-cream/70 pt-12 pb-6 px-6">
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
      <div>
        <div class="text-gold font-display text-lg font-semibold tracking-widest uppercase mb-1">Andaya's</div>
        <div class="text-cream/40 text-[10px] tracking-[0.2em] uppercase mb-4">Native Products</div>
        <p class="text-cream/60 text-sm font-light leading-relaxed">Nagbibigay ng masustansya at masarap na native products mula sa aming pamilya para sa inyo.</p>
      </div>
      <div>
        <h4 class="text-cream/90 text-xs tracking-[0.2em] uppercase font-medium mb-4">Mabilis na Links</h4>
        <ul class="flex flex-col gap-2 text-sm font-light">
          <li><a href="#hero" class="text-cream/60 hover:text-gold transition-colors">Home</a></li>
          <li><a href="#featured" class="text-cream/60 hover:text-gold transition-colors">Mga Produkto</a></li>
          <li><a href="{{ route('login') }}" class="text-cream/60 hover:text-gold transition-colors">Sign In</a></li>
          <li><a href="{{ route('register') }}" class="text-cream/60 hover:text-gold transition-colors">Register</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-cream/90 text-xs tracking-[0.2em] uppercase font-medium mb-4">Makipag-ugnayan</h4>
        <ul class="flex flex-col gap-3 text-sm font-light">
          <li class="flex items-start gap-2 text-cream/60"><span>📧</span> andayantiveproduct1@gmail.com</li>
          <li class="flex items-start gap-2 text-cream/60"><span>📍</span> Andaya St., Quezon City, Philippines</li>
          <li class="flex items-start gap-2 text-cream/60"><span>📞</span> +63 912 345 6789</li>
        </ul>
      </div>
    </div>
    <div class="border-t border-cream/10 pt-6 text-center text-cream/30 text-xs tracking-[0.2em] uppercase">
      © 2025 Andaya's Native Products · Lahat ng karapatan ay nakalaan.
    </div>
  </footer>

  <script>
    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.15 });
    reveals.forEach(r => observer.observe(r));

    // Active nav on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[data-section]');
    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(s => {
        if (window.scrollY >= s.offsetTop - 80) current = s.id;
      });
      navLinks.forEach(link => {
        link.classList.remove('active-section', 'text-gold');
        link.classList.add('text-cream/70');
        if (link.dataset.section === current) {
          link.classList.add('active-section', 'text-gold');
          link.classList.remove('text-cream/70');
        }
      });
    });
  </script>

</body>
</html>
