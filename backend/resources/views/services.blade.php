<!DOCTYPE html>
<html lang="fil">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Services – Andaya's Native Products</title>
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
    .anim-6 { animation: fadeUp 0.7s ease 0.75s both; }

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
    .logo-ring { border: 1.5px solid rgba(184, 146, 74, 0.4); }

    .service-card {
      background: linear-gradient(135deg, rgba(245,240,232,0.8) 0%, rgba(237,228,208,0.6) 100%);
      border: 1px solid rgba(44, 26, 14, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .service-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(44,26,14,0.1);
    }
    .btn-primary {
      position: relative;
      overflow: hidden;
    }
    .btn-primary::after {
      content: '';
      position: absolute;
      top: 0; left: -100%;
      width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
      transition: left 0.5s ease;
    }
    .btn-primary:hover::after { left: 100%; }
  </style>
</head>
<body class="bg-cream font-sans min-h-screen">

  <!-- NAVBAR -->
  <nav class="anim-1 fixed top-0 left-0 right-0 z-40 bg-bark/95 backdrop-blur-sm px-6 md:px-10 h-14 flex items-center justify-between shadow-sm">
    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
      <div class="logo-ring w-9 h-9 rounded-full flex items-center justify-center bg-bark-mid/60">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 2C6 2 3 7 3 12c0 6 5 10 9 10 1-3 1-7-1-10 3 2 5 6 5 10 4-2 5-6 5-10C21 7 18 2 12 2z" fill="#b8924a" opacity="0.9"/>
          <path d="M12 12 Q12 7 12 2" stroke="#d4aa6a" stroke-width="0.8" opacity="0.6"/>
        </svg>
      </div>
      <div class="leading-tight">
        <div class="text-gold font-display text-sm font-semibold tracking-widest uppercase">Andaya's</div>
        <div class="text-gold-light/60 text-[9px] tracking-[0.2em] uppercase font-sans font-light">Native Products</div>
      </div>
    </a>
    <div class="flex items-center gap-7">
      <a href="{{ route('home') }}" class="nav-link text-xs tracking-[0.15em] uppercase font-light transition-colors duration-200 {{ request()->routeIs('home') ? 'text-gold border-b border-gold pb-0.5' : 'text-cream/70 hover:text-cream' }}">Home</a>
      <a href="{{ route('about') }}" class="nav-link text-xs tracking-[0.15em] uppercase font-light transition-colors duration-200 {{ request()->routeIs('about') ? 'text-gold border-b border-gold pb-0.5' : 'text-cream/70 hover:text-cream' }}">About Us</a>
      <a href="{{ route('services') }}" class="nav-link text-xs tracking-[0.15em] uppercase font-light transition-colors duration-200 {{ request()->routeIs('services') ? 'text-gold border-b border-gold pb-0.5' : 'text-cream/70 hover:text-cream' }}">Services</a>
      <a href="{{ route('login') }}" class="ml-2 px-4 py-1.5 border text-xs tracking-[0.15em] uppercase font-light transition-all duration-300 rounded-sm {{ request()->routeIs('login') ? 'bg-gold text-bark border-gold' : 'border-gold/50 text-gold hover:bg-gold hover:text-bark' }}">Sign In</a>
    </div>
  </nav>

  <!-- MAIN -->
  <main class="min-h-screen px-5 pt-28 pb-16">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] rounded-full bg-gold/5 blur-3xl"></div>
      <div class="absolute bottom-1/4 right-1/4 w-[300px] h-[300px] rounded-full bg-rust/5 blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto">

      <!-- Eyebrow -->
      <div class="anim-2 flex justify-center mb-6">
        <span class="divider-ornament text-gold text-[9px] tracking-[0.35em] uppercase font-sans font-medium">
          Ang Aming Alok
        </span>
      </div>

      <!-- Heading -->
      <div class="anim-3 text-center mb-2">
        <h1 class="font-display text-5xl md:text-6xl font-light text-bark leading-[1.1] tracking-tight">Mga</h1>
        <h1 class="font-display text-5xl md:text-6xl font-light italic brand-italic leading-[1.05] tracking-tight">Serbisyo Namin</h1>
      </div>

      <div class="anim-3 flex justify-center my-7">
        <div class="w-12 h-px bg-gradient-to-r from-transparent via-gold to-transparent"></div>
      </div>

      <p class="anim-4 text-center text-bark-mid/70 font-sans text-sm leading-relaxed font-light max-w-lg mx-auto mb-12">
        Nag-aalok kami ng iba't ibang serbisyo para matulungan kayo na mahanap, mag-order, at makatanggap ng mga pinakamainam na produktong Pilipino.
      </p>

      <!-- Services Grid -->
      <div class="anim-5 grid grid-cols-1 md:grid-cols-2 gap-5 mb-14">

        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="text-2xl mt-0.5">🛒</div>
            <div>
              <h3 class="font-display text-xl text-bark font-medium mb-2">Online Ordering</h3>
              <p class="text-bark-mid/60 text-xs leading-relaxed font-sans font-light">Mag-order ng inyong mga paboritong produkto nang madali at mabilis sa pamamagitan ng aming sistema.</p>
            </div>
          </div>
        </div>

        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="text-2xl mt-0.5">📦</div>
            <div>
              <h3 class="font-display text-xl text-bark font-medium mb-2">Inventory Management</h3>
              <p class="text-bark-mid/60 text-xs leading-relaxed font-sans font-light">Real-time na pagsubaybay ng mga produkto para masiguro na laging available ang inyong mga kailangan.</p>
            </div>
          </div>
        </div>

        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="text-2xl mt-0.5">🚚</div>
            <div>
              <h3 class="font-display text-xl text-bark font-medium mb-2">Delivery Tracking</h3>
              <p class="text-bark-mid/60 text-xs leading-relaxed font-sans font-light">Subaybayan ang inyong mga order mula sa aming bodega hanggang sa inyong pintuan.</p>
            </div>
          </div>
        </div>

        <div class="service-card rounded-xl p-8 relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="flex items-start gap-4">
            <div class="text-2xl mt-0.5">📊</div>
            <div>
              <h3 class="font-display text-xl text-bark font-medium mb-2">Sales Reports</h3>
              <p class="text-bark-mid/60 text-xs leading-relaxed font-sans font-light">Detalyadong ulat ng mga benta at transaksyon para sa mas matalinong pamamahala ng negosyo.</p>
            </div>
          </div>
        </div>

      </div>

      <!-- CTA -->
      <div class="anim-6 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('register') }}" class="btn-primary flex items-center justify-center gap-2.5 bg-bark text-cream text-xs tracking-[0.18em] uppercase font-medium px-7 py-3.5 rounded-sm hover:bg-bark-mid transition-colors duration-300 shadow-md shadow-bark/20">
          Magsimula Na
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </a>
        <a href="{{ route('about') }}" class="flex items-center justify-center gap-2 border border-bark/25 text-bark text-xs tracking-[0.18em] uppercase font-medium px-7 py-3.5 rounded-sm hover:border-gold hover:text-rust transition-all duration-300">
          Alamin ang Higit Pa
        </a>
      </div>

    </div>
  </main>

  <!-- FOOTER -->
  <div class="fixed bottom-0 left-0 right-0 py-3 flex justify-center">
    <div class="text-bark/30 text-[10px] tracking-[0.2em] uppercase font-sans">
      © 2025 Andaya's Native Products · Pilipinas
    </div>
  </div>

</body>
</html>
