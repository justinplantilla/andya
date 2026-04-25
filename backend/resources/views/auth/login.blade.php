<!DOCTYPE html>
<html lang="fil">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In – Andaya's Native Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            cream: '#f5f0e8', 'cream-dark': '#ede8dc',
            bark: '#2c1a0e', 'bark-mid': '#4a2e1a',
            rust: '#8b3a1a', gold: '#b8924a',
            'gold-light': '#d4aa6a', sage: '#6b7c5a',
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
      content: ''; position: fixed; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.06'/%3E%3C/svg%3E");
      pointer-events: none; z-index: 50; opacity: 0.4;
    }
    .hero-card {
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(107,124,90,0.07) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(184,146,74,0.08) 0%, transparent 50%),
        linear-gradient(135deg, #f5f0e8 0%, #ede4d0 100%);
    }
    .hero-card::before {
      content: ''; position: absolute; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234a2e1a' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.5; border-radius: inherit;
    }
    .brand-italic {
      background: linear-gradient(135deg, #8b3a1a 0%, #b8924a 50%, #8b3a1a 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .divider-ornament::before, .divider-ornament::after {
      content: ''; display: inline-block; width: 40px; height: 1px;
      background: linear-gradient(90deg, transparent, #b8924a);
      vertical-align: middle; margin: 0 10px;
    }
    .divider-ornament::after { background: linear-gradient(90deg, #b8924a, transparent); }
    .btn-primary { position: relative; overflow: hidden; }
    .btn-primary::after {
      content: ''; position: absolute; top: 0; left: -100%;
      width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
      transition: left 0.5s ease;
    }
    .btn-primary:hover::after { left: 100%; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .anim-1 { animation: fadeIn 0.6s ease forwards; }
    .anim-2 { animation: fadeUp 0.7s ease 0.15s both; }
    .anim-3 { animation: fadeUp 0.7s ease 0.30s both; }
    .anim-4 { animation: fadeUp 0.7s ease 0.45s both; }
    .anim-5 { animation: fadeUp 0.7s ease 0.60s both; }
    .nav-link { position: relative; }
    .nav-link::after {
      content: ''; position: absolute; bottom: -2px; left: 50%;
      width: 0; height: 1px; background: #b8924a;
      transition: all 0.3s ease; transform: translateX(-50%);
    }
    .nav-link:hover::after { width: 100%; }
    .logo-ring { border: 1.5px solid rgba(184,146,74,0.4); }
    .input-field {
      width: 100%; background: rgba(255,255,255,0.6);
      border: 1px solid rgba(44,26,14,0.15); border-radius: 2px;
      padding: 14px 16px; font-family: 'Jost', sans-serif;
      font-size: 15px; color: #2c1a0e; outline: none;
      transition: border-color 0.3s ease, background 0.3s ease;
    }
    .input-field::placeholder { color: rgba(74,46,26,0.4); }
    .input-field:focus { border-color: rgba(184,146,74,0.6); background: rgba(255,255,255,0.85); }
  </style>
</head>
<body class="bg-cream font-sans min-h-screen">

  <!-- NAVBAR -->
  <nav class="anim-1 fixed top-0 left-0 right-0 z-40 bg-bark/95 backdrop-blur-sm px-6 md:px-10 h-16 flex items-center justify-between shadow-sm">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
      <div class="logo-ring w-10 h-10 rounded-full flex items-center justify-center bg-bark-mid/60">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
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
      <a href="{{ route('home') }}" class="nav-link text-sm tracking-[0.15em] uppercase font-light transition-colors duration-200 {{ request()->routeIs('home') ? 'text-gold border-b border-gold pb-0.5' : 'text-cream/70 hover:text-cream' }}">Home</a>
      <a href="{{ route('about') }}" class="nav-link text-sm tracking-[0.15em] uppercase font-light transition-colors duration-200 {{ request()->routeIs('about') ? 'text-gold border-b border-gold pb-0.5' : 'text-cream/70 hover:text-cream' }}">About Us</a>
      <a href="{{ route('services') }}" class="nav-link text-sm tracking-[0.15em] uppercase font-light transition-colors duration-200 {{ request()->routeIs('services') ? 'text-gold border-b border-gold pb-0.5' : 'text-cream/70 hover:text-cream' }}">Services</a>
      <a href="{{ route('register') }}" class="ml-2 px-5 py-2 border text-sm tracking-[0.15em] uppercase font-light transition-all duration-300 rounded-sm {{ request()->routeIs('register') ? 'bg-gold text-bark border-gold' : 'border-gold/50 text-gold hover:bg-gold hover:text-bark' }}">Register</a>
    </div>
  </nav>

  <!-- MAIN -->
  <main class="min-h-screen flex items-center justify-center px-5 pt-16 pb-10">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] rounded-full bg-gold/5 blur-3xl"></div>
      <div class="absolute bottom-1/4 right-1/4 w-[300px] h-[300px] rounded-full bg-rust/5 blur-3xl"></div>
    </div>

    <div class="relative hero-card rounded-2xl border border-bark/10 shadow-2xl shadow-bark/15 w-full max-w-lg px-12 py-14 overflow-hidden">
      <div class="absolute top-4 left-4 w-6 h-6 border-t border-l border-gold/40"></div>
      <div class="absolute top-4 right-4 w-6 h-6 border-t border-r border-gold/40"></div>
      <div class="absolute bottom-4 left-4 w-6 h-6 border-b border-l border-gold/40"></div>
      <div class="absolute bottom-4 right-4 w-6 h-6 border-b border-r border-gold/40"></div>

      <div class="anim-2 flex justify-center mb-6">
        <span class="divider-ornament text-gold text-xs tracking-[0.35em] uppercase font-sans font-medium">Maligayang Pagbabalik</span>
      </div>

      <div class="anim-3 text-center mb-2">
        <h1 class="font-display text-5xl md:text-6xl font-light text-bark leading-[1.1] tracking-tight">Mag-sign in sa</h1>
        <h1 class="font-display text-5xl md:text-6xl font-light italic brand-italic leading-[1.05] tracking-tight">Iyong Account</h1>
      </div>

      <div class="anim-3 flex justify-center my-6">
        <div class="w-12 h-px bg-gradient-to-r from-transparent via-gold to-transparent"></div>
      </div>

      <form method="POST" action="{{ route('login.post') }}" class="anim-4 flex flex-col gap-5">
        @csrf

        @if($errors->any())
          <div class="text-rust text-sm text-center tracking-wide bg-rust/10 py-3 px-4 rounded">
            {{ $errors->first() }}
          </div>
        @endif

        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-[0.2em] uppercase text-bark-mid/70 font-sans font-medium">Email</label>
          <input type="email" name="email" placeholder="email@halimbawa.com" class="input-field" value="{{ old('email') }}" required/>
        </div>

        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-[0.2em] uppercase text-bark-mid/70 font-sans font-medium">Password</label>
          <input type="password" name="password" placeholder="••••••••" class="input-field" required/>
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="accent-gold w-4 h-4"/>
            <span class="text-sm text-bark-mid/70 tracking-wide">Remember me</span>
          </label>
          <a href="#" class="text-sm text-gold hover:text-rust transition-colors tracking-wide">Nakalimutan ang password?</a>
        </div>

        <button type="submit" class="btn-primary mt-1 bg-bark text-cream text-sm tracking-[0.18em] uppercase font-medium px-7 py-4 rounded-sm hover:bg-bark-mid transition-colors duration-300 shadow-md shadow-bark/20">
          Pumasok
        </button>
      </form>

      <div class="anim-5 text-center mt-7">
        <span class="text-sm text-bark-mid/60 tracking-wide">Wala pang account? </span>
        <a href="{{ route('register') }}" class="text-sm text-gold hover:text-rust transition-colors tracking-wide font-medium">Mag-register</a>
      </div>
    </div>
  </main>

  <footer class="py-5 flex justify-center border-t border-bark/10 mt-8">
    <div class="text-bark/40 text-xs tracking-[0.2em] uppercase font-sans">© 2025 Andaya's Native Products · Pilipinas</div>
  </footer>

</body>
</html>
