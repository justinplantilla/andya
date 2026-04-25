<!DOCTYPE html>
<html lang="fil">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard – Andaya's</title>
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
      pointer-events: none; z-index: 50; opacity: 0.3;
    }
    .sidebar {
      background: linear-gradient(180deg, #2c1a0e 0%, #3d2314 50%, #4a2e1a 100%);
    }
    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 16px; border-radius: 8px;
      font-size: 13px; letter-spacing: 0.05em;
      color: rgba(245,240,232,0.55);
      transition: all 0.2s ease; cursor: pointer;
      text-decoration: none; width: 100%; text-align: left;
      background: none; border: none;
    }
    .nav-item:hover { background: rgba(184,146,74,0.12); color: #d4aa6a; }
    .nav-item.active {
      background: linear-gradient(135deg, rgba(184,146,74,0.2), rgba(184,146,74,0.08));
      color: #d4aa6a; border-left: 2px solid #b8924a;
    }
    .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }

    .sub-item {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 16px 8px 44px; border-radius: 8px;
      font-size: 12px; letter-spacing: 0.05em;
      color: rgba(245,240,232,0.4);
      transition: all 0.2s ease; text-decoration: none;
    }
    .sub-item:hover { background: rgba(184,146,74,0.08); color: #d4aa6a; }

    .stat-card {
      background: linear-gradient(135deg, #f5f0e8 0%, #ede4d0 100%);
      border: 1px solid rgba(184,146,74,0.2); border-radius: 14px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(44,26,14,0.1); }
    .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }

    .logo-ring { border: 1.5px solid rgba(184,146,74,0.35); }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(184,146,74,0.3); border-radius: 10px; }
  </style>
</head>
<body class="bg-cream font-sans min-h-screen flex">

  <!-- ───── SIDEBAR ───── -->
  <aside class="sidebar fixed top-0 left-0 h-full w-60 flex flex-col z-30 shadow-2xl">

    <!-- Logo -->
    <div class="px-5 py-6 border-b border-white/5">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="logo-ring w-9 h-9 rounded-full flex items-center justify-center bg-bark-mid/60">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M12 2C6 2 3 7 3 12c0 6 5 10 9 10 1-3 1-7-1-10 3 2 5 6 5 10 4-2 5-6 5-10C21 7 18 2 12 2z" fill="#b8924a" opacity="0.9"/>
          </svg>
        </div>
        <div>
          <div class="text-gold font-display text-sm font-semibold tracking-widest uppercase">Andaya's</div>
          <div class="text-gold-light/40 text-[9px] tracking-[0.2em] uppercase font-light">Native Products</div>
        </div>
      </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-5 flex flex-col gap-1" x-data="{ productsOpen: false }">
      <div class="text-gold/30 text-[9px] tracking-[0.25em] uppercase px-3 mb-2">Menu</div>

      <a href="{{ route('admin.dashboard') }}" class="nav-item active">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
      </a>

      <!-- Products with dropdown -->
      <div>
        <button @click="productsOpen = !productsOpen" class="nav-item">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          <span class="flex-1 text-left">Products</span>
          <svg :class="productsOpen ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div x-show="productsOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 flex flex-col gap-0.5">
          <a href="{{ route('admin.products.index') }}" class="sub-item">All Products</a>
          <a href="{{ route('admin.products.create') }}" class="sub-item">Add Product</a>
        </div>
      </div>

      <a href="{{ route('admin.categories.index') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        Category
      </a>

      <a href="{{ route('admin.inventory.index') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
        </svg>
        Inventory
      </a>

      <a href="{{ route('admin.orders.index') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Orders
      </a>

      <a href="{{ route('admin.purchases.index') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Purchases
      </a>

      <a href="{{ route('admin.attributes.index') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        Attributes
      </a>

      <a href="{{ route('admin.invoices.index') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Invoices
      </a>

      <div class="text-gold/30 text-[9px] tracking-[0.25em] uppercase px-3 mt-5 mb-2">System</div>

      <a href="{{ route('admin.settings') }}" class="nav-item">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Settings
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-item hover:!bg-rust/20 hover:!text-rust/80">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Logout
        </button>
      </form>
    </nav>

    <!-- Footer -->
    <div class="px-5 py-4 border-t border-white/5">
      <div class="text-cream/20 text-[9px] tracking-[0.15em] uppercase">© 2025 Andaya's</div>
    </div>
  </aside>

  <!-- ───── MAIN CONTENT ───── -->
  <div class="ml-60 flex-1 min-h-screen flex flex-col">

    <!-- Top Bar -->
    <header class="sticky top-0 z-20 bg-cream/80 backdrop-blur-sm border-b border-bark/8 px-8 h-16 flex items-center justify-between">
      <div>
        <h1 class="font-display text-2xl text-bark font-light tracking-tight">Dashboard</h1>
        <p class="text-bark-mid/50 text-xs tracking-wide">Maligayang pagbabalik, <span class="text-gold">{{ Auth::user()->name }}</span></p>
      </div>
      <div class="flex items-center gap-3">
        <button class="w-9 h-9 rounded-full bg-bark/5 hover:bg-bark/10 flex items-center justify-center transition-colors">
          <svg class="w-4 h-4 text-bark-mid/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
        </button>
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold/40 to-bark-mid flex items-center justify-center text-cream font-display text-base font-medium">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 px-8 py-8">

      <!-- STAT CARDS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="stat-card p-5">
          <div class="flex items-start justify-between mb-4">
            <div class="stat-icon bg-gold/15">
              <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Total</span>
          </div>
          <div class="font-display text-4xl text-bark font-light">0</div>
          <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Products</div>
        </div>

        <div class="stat-card p-5">
          <div class="flex items-start justify-between mb-4">
            <div class="stat-icon bg-gold/15">
              <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Active</span>
          </div>
          <div class="font-display text-4xl text-bark font-light">0</div>
          <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Pending Orders</div>
        </div>

        <div class="stat-card p-5">
          <div class="flex items-start justify-between mb-4">
            <div class="stat-icon bg-sage/15">
              <svg class="w-5 h-5 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Done</span>
          </div>
          <div class="font-display text-4xl text-bark font-light">0</div>
          <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Delivered</div>
        </div>

        <div class="stat-card p-5">
          <div class="flex items-start justify-between mb-4">
            <div class="stat-icon bg-rust/10">
              <svg class="w-5 h-5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Revenue</span>
          </div>
          <div class="font-display text-4xl text-bark font-light">₱0</div>
          <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">This Month</div>
        </div>

      </div>

      <!-- BOTTOM GRID -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-xl text-bark font-medium">Mga Kamakailang Order</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-gold hover:text-rust transition-colors tracking-wide">Tingnan Lahat →</a>
          </div>
          <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <p class="text-bark-mid/50 text-sm font-sans">Wala pang mga order.</p>
          </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-xl text-bark font-medium">Low Stock</h2>
            <a href="{{ route('admin.inventory.index') }}" class="text-xs text-gold hover:text-rust transition-colors tracking-wide">Lahat →</a>
          </div>
          <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
              </svg>
            </div>
            <p class="text-bark-mid/50 text-sm font-sans">Lahat ng stock ay sapat.</p>
          </div>
        </div>

      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
