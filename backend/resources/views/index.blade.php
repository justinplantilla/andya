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

    .hero-card {
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(107, 124, 90, 0.07) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(184, 146, 74, 0.08) 0%, transparent 50%),
        linear-gradient(135deg, #f5f0e8 0%, #ede4d0 100%);
    }
    .hero-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234a2e1a' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.5;
      border-radius: inherit;
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
      <div class="logo-ring w-9 h-9 rounded-full flex items-center justify-center bg-bark-mid/60">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 2C6 2 3 7 3 12c0 6 5 10 9 10 1-3 1-7-1-10 3 2 5 6 5 10 4-2 5-6 5-10C21 7 18 2 12 2z" fill="#b8924a" opacity="0.9"/>
          <path d="M12 12 Q12 7 12 2" stroke="#d4aa6a" stroke-width="0.8" opacity="0.6"/>
        </svg>
      </div>
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
    </div>
  </nav>

  <!-- ───── HERO SECTION ───── -->
  <section id="hero" class="min-h-screen flex items-center justify-center px-5 pt-14">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] rounded-full bg-gold/5 blur-3xl"></div>
      <div class="absolute bottom-1/4 right-1/4 w-[300px] h-[300px] rounded-full bg-rust/5 blur-3xl"></div>
    </div>

    <div class="relative hero-card rounded-2xl border border-bark/10 shadow-2xl shadow-bark/15 w-full max-w-2xl px-14 py-16 overflow-hidden">
      <div class="absolute top-4 left-4 w-5 h-5 border-t border-l border-gold/40"></div>
      <div class="absolute top-4 right-4 w-5 h-5 border-t border-r border-gold/40"></div>
      <div class="absolute bottom-4 left-4 w-5 h-5 border-b border-l border-gold/40"></div>
      <div class="absolute bottom-4 right-4 w-5 h-5 border-b border-r border-gold/40"></div>

      <div class="anim-2 flex justify-center mb-6">
        <span class="divider-ornament text-gold text-xs tracking-[0.35em] uppercase font-sans font-medium">Mga Likhain ng Pilipinas</span>
      </div>

      <div class="anim-3 text-center mb-2">
        <h1 class="font-display text-6xl md:text-7xl font-light text-bark leading-[1.1] tracking-tight">Andaya's</h1>
        <h1 class="font-display text-6xl md:text-7xl font-light italic brand-italic leading-[1.05] tracking-tight">Native Products</h1>
      </div>

      <div class="anim-3 flex justify-center my-6">
        <div class="w-12 h-px bg-gradient-to-r from-transparent via-gold to-transparent"></div>
      </div>

      <p class="anim-4 text-center text-bark-mid/80 font-sans text-base leading-relaxed font-light max-w-sm mx-auto">
        Isang modernong portal para sa mga taong nagmamahal sa tunay na likha ng Pilipino — dinisenyo para sa husay, iningatan para sa kalikasan.
      </p>

      <div class="anim-5 flex flex-col sm:flex-row gap-3 mt-9 justify-center">
        <a href="{{ route('login') }}" class="btn-primary flex items-center justify-center gap-2.5 bg-bark text-cream text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:bg-bark-mid transition-colors duration-300 shadow-md shadow-bark/20">
          Pumasok sa Dashboard
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </a>
        <a href="#about" class="flex items-center justify-center gap-2 border border-bark/25 text-bark text-sm tracking-[0.18em] uppercase font-medium px-8 py-4 rounded-sm hover:border-gold hover:text-rust transition-all duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-sage" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 5.5-8 5.5C14 8 17 8 17 8z"/>
          </svg>
          Alamin ang Higit Pa
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
          Ang Andaya's Native Products ay isang negosyong nakatuon sa pagpapahalaga at pagpapakalat ng mga tunay na likhain ng Pilipino. Mula sa mga produktong gawa sa natural na materyales hanggang sa mga tradisyonal na pagkain, bawat piraso ay may kwentong nagmumula sa puso ng ating kultura.
        </p>
      </div>

      <div class="reveal grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="value-card rounded-xl p-8 text-center relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="text-4xl mb-4">🌿</div>
          <h3 class="font-display text-2xl text-bark font-medium mb-3">Kalikasan</h3>
          <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Gumagamit kami ng mga natural at sustainable na materyales para sa bawat produkto.</p>
        </div>
        <div class="value-card rounded-xl p-8 text-center relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="text-4xl mb-4">🤝</div>
          <h3 class="font-display text-2xl text-bark font-medium mb-3">Komunidad</h3>
          <p class="text-bark-mid/70 text-sm leading-relaxed font-sans font-light">Sumusuporta kami sa mga lokal na artisano at magsasaka sa buong Pilipinas.</p>
        </div>
        <div class="value-card rounded-xl p-8 text-center relative overflow-hidden">
          <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
          <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
          <div class="text-4xl mb-4">✨</div>
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
            <div class="text-2xl mt-0.5">🛒</div>
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
            <div class="text-2xl mt-0.5">📦</div>
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
            <div class="text-2xl mt-0.5">🚚</div>
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
            <div class="text-2xl mt-0.5">📊</div>
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
  <footer class="py-6 flex justify-center border-t border-bark/10">
    <div class="text-bark/40 text-xs tracking-[0.2em] uppercase font-sans">
      © 2025 Andaya's Native Products · Pilipinas
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
