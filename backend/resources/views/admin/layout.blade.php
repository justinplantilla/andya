<!DOCTYPE html>
<html lang="fil">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Admin') – Andaya's</title>
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
    .sidebar { background: linear-gradient(180deg, #1e1208 0%, #2c1a0e 40%, #3a2210 100%); }
    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 16px; border-radius: 10px;
      font-size: 14px; letter-spacing: 0.03em;
      color: rgba(245,240,232,0.72);
      transition: all 0.2s ease; cursor: pointer;
      text-decoration: none; width: 100%; text-align: left;
      background: none; border: none;
    }
    .nav-item:hover { background: rgba(184,146,74,0.15); color: #e8c97a; }
    .nav-item.active {
      background: linear-gradient(135deg, rgba(184,146,74,0.25), rgba(184,146,74,0.10));
      color: #e8c97a; border-left: 3px solid #d4aa6a;
      padding-left: 13px;
    }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.85; }
    .nav-item.active svg, .nav-item:hover svg { opacity: 1; }
    .sub-item {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 16px 9px 46px; border-radius: 8px;
      font-size: 13px; letter-spacing: 0.03em;
      color: rgba(245,240,232,0.55);
      transition: all 0.2s ease; text-decoration: none;
    }
    .sub-item:hover { background: rgba(184,146,74,0.10); color: #e8c97a; }
    .sub-item.active { color: #d4aa6a; background: rgba(184,146,74,0.08); }
    .stat-card {
      background: linear-gradient(135deg, #f5f0e8 0%, #ede4d0 100%);
      border: 1px solid rgba(184,146,74,0.2); border-radius: 14px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(44,26,14,0.1); }
    .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .logo-ring { border: 1.5px solid rgba(184,146,74,0.35); }
    .btn-gold {
      background: linear-gradient(135deg, #b8924a, #d4aa6a);
      color: #2c1a0e; font-size: 13px; font-weight: 500;
      padding: 9px 18px; border-radius: 6px; border: none;
      cursor: pointer; transition: opacity 0.2s; letter-spacing: 0.05em;
      text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-gold:hover { opacity: 0.88; }
    .btn-outline {
      background: transparent; color: #4a2e1a;
      font-size: 13px; padding: 9px 18px; border-radius: 6px;
      border: 1px solid rgba(44,26,14,0.2); cursor: pointer;
      transition: all 0.2s; letter-spacing: 0.05em;
      text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-outline:hover { border-color: #b8924a; color: #b8924a; }
    .table-row { border-bottom: 1px solid rgba(184,146,74,0.1); }
    .table-row:hover { background: rgba(184,146,74,0.04); }
    .table-row td { font-size: 14px; }
    table thead th { font-size: 11px; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .badge-pending    { background: rgba(184,146,74,0.15); color: #b8924a; }
    .badge-confirmed  { background: rgba(59,130,246,0.12);  color: #2563eb; }
    .badge-processing { background: rgba(168,85,247,0.12);  color: #7c3aed; }
    .badge-shipped    { background: rgba(14,165,233,0.12);  color: #0284c7; }
    .badge-active     { background: rgba(107,124,90,0.15);  color: #6b7c5a; }
    .badge-inactive   { background: rgba(139,58,26,0.12);   color: #8b3a1a; }
    .badge-delivered  { background: rgba(107,124,90,0.15);  color: #6b7c5a; }
    .badge-cancelled  { background: rgba(139,58,26,0.12);   color: #8b3a1a; }
    .input-field {
      width: 100%; background: rgba(255,255,255,0.7);
      border: 1px solid rgba(44,26,14,0.15); border-radius: 6px;
      padding: 10px 14px; font-family: 'Jost', sans-serif;
      font-size: 14px; color: #2c1a0e; outline: none;
      transition: border-color 0.2s;
    }
    .input-field:focus { border-color: rgba(184,146,74,0.6); }
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-thumb { background: rgba(184,146,74,0.3); border-radius: 10px; }
  </style>
</head>
<body class="bg-cream font-sans min-h-screen flex">

  <!-- SIDEBAR -->
  <aside class="sidebar fixed top-0 left-0 h-full w-64 flex flex-col z-30 shadow-2xl">
    <div class="px-5 py-6 border-b border-white/5">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="logo-ring w-9 h-9 rounded-full flex items-center justify-center bg-bark-mid/60">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M12 2C6 2 3 7 3 12c0 6 5 10 9 10 1-3 1-7-1-10 3 2 5 6 5 10 4-2 5-6 5-10C21 7 18 2 12 2z" fill="#b8924a" opacity="0.9"/>
          </svg>
        </div>
        <div>
          <div class="text-gold-light font-display text-base font-semibold tracking-widest uppercase">Andaya's</div>
          <div class="text-gold-light/70 text-[9px] tracking-[0.2em] uppercase font-medium">Admin Panel</div>
        </div>
      </a>
    </div>


    <nav class="flex-1 px-3 py-4 flex flex-col gap-0.5 overflow-y-auto" x-data="{ productsOpen: {{ request()->routeIs('admin.products.*') ? 'true' : 'false' }} }">
      <div class="text-gold/50 text-[10px] tracking-[0.25em] uppercase px-3 mb-2 font-medium">Main</div>

      <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>

      <div>
        <button @click="productsOpen = !productsOpen" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <span class="flex-1 text-left">Products</span>
          <svg :class="productsOpen ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="productsOpen" class="mt-0.5 flex flex-col gap-0.5">
          <a href="{{ route('admin.products.index') }}" class="sub-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">All Products</a>
          <a href="{{ route('admin.products.create') }}" class="sub-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">Add Product</a>
        </div>
      </div>

      <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
        Categories
      </a>

      <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        Inventory
      </a>

      <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Orders
      </a>

      <a href="{{ route('admin.purchases.index') }}" class="nav-item {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Purchases
      </a>

      <a href="{{ route('admin.invoices.index') }}" class="nav-item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Invoices
      </a>

      <div class="text-gold/50 text-[10px] tracking-[0.25em] uppercase px-3 mt-4 mb-2 font-medium">System</div>

      <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Reports
      </a>

      <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Customers
      </a>

      <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Settings
      </a>

      <a href="{{ route('logout.get') }}" class="nav-item hover:!bg-rust/20 hover:!text-rust/80">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Logout
      </a>
    </nav>

    <div class="px-5 py-4 border-t border-white/8">
      <div class="text-cream/30 text-[9px] tracking-[0.15em] uppercase">© 2025 Andaya's</div>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="ml-64 flex-1 min-h-screen flex flex-col">
    <header class="sticky top-0 z-20 bg-cream/80 backdrop-blur-sm border-b border-bark/8 px-8 h-16 flex items-center justify-between">
      <div>
        <h1 class="font-display text-2xl text-bark font-light tracking-tight">@yield('page-title', 'Dashboard')</h1>
        <p class="text-bark-mid/50 text-xs tracking-wide">@yield('page-subtitle', 'Overview ng sistema')</p>
      </div>
      <div class="flex items-center gap-3">
        @php
          $unreadNotifs = \App\Models\Notification::unread()->latest()->take(10)->get();
          $unreadCount  = $unreadNotifs->count();
        @endphp

        <!-- Notification Bell -->
        <div class="relative" id="notif-wrapper">
          <button onclick="toggleNotif()" class="relative w-9 h-9 rounded-full bg-bark/5 hover:bg-bark/10 flex items-center justify-center transition-colors">
            <svg class="w-4 h-4 text-bark-mid/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @if($unreadCount > 0)
              <span class="absolute -top-1 -right-1 bg-rust text-cream text-[9px] font-bold min-w-[16px] h-4 rounded-full flex items-center justify-center px-1">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
            @endif
          </button>

          <!-- Dropdown -->
          <div id="notif-dropdown" class="hidden absolute right-0 top-11 w-80 bg-cream rounded-2xl border border-gold/20 shadow-2xl z-50 overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gold/15">
              <span class="text-sm font-semibold text-bark">Notifications</span>
              @if($unreadCount > 0)
                <form method="POST" action="{{ route('admin.notifications.read') }}">
                  @csrf
                  <button type="submit" class="text-xs text-gold hover:text-rust transition-colors">Mark all as read</button>
                </form>
              @endif
            </div>

            <div class="max-h-80 overflow-y-auto">
              @forelse($unreadNotifs as $notif)
                <form method="POST" action="{{ route('admin.notifications.readOne', $notif) }}">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gold/8 transition-colors border-b border-gold/8 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gold/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                      <svg class="w-4 h-4 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="text-sm font-medium text-bark">{{ $notif->title }}</div>
                      <div class="text-xs text-bark-mid/60 mt-0.5 leading-relaxed">{{ $notif->message }}</div>
                      <div class="text-[10px] text-bark-mid/40 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="w-2 h-2 rounded-full bg-gold mt-2 flex-shrink-0"></div>
                  </button>
                </form>
              @empty
                <div class="flex flex-col items-center justify-center py-10 text-center">
                  <svg class="w-8 h-8 text-bark/20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                  <p class="text-xs text-bark-mid/40">Wala pang notifications</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold/40 to-bark-mid flex items-center justify-center text-cream font-display text-base font-medium">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
      </div>
    </header>

    <main class="flex-1 px-8 py-8">
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    function toggleNotif() {
      const d = document.getElementById('notif-dropdown');
      d.classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
      const wrapper = document.getElementById('notif-wrapper');
      if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notif-dropdown').classList.add('hidden');
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
      const searchInputs = document.querySelectorAll('input[name="search"]');
      searchInputs.forEach(function (input) {
        const form = input.closest('form');
        if (!form) return;

        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.display = 'inline-block';
        wrapper.style.width = input.offsetWidth + 'px';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        const dropdown = document.createElement('div');
        dropdown.style.cssText = 'position:absolute;top:100%;left:0;right:0;background:#f5f0e8;border:1px solid rgba(184,146,74,0.3);border-radius:6px;box-shadow:0 8px 24px rgba(44,26,14,0.1);z-index:100;display:none;max-height:220px;overflow-y:auto;margin-top:4px;';
        wrapper.appendChild(dropdown);

        let debounceTimer;
        input.addEventListener('input', function () {
          clearTimeout(debounceTimer);
          const val = input.value.trim();
          if (val.length === 0) {
            dropdown.style.display = 'none';
            debounceTimer = setTimeout(() => form.submit(), 400);
            return;
          }
          dropdown.innerHTML = '<div style="padding:10px 14px;font-size:12px;color:rgba(74,46,26,0.5);font-family:Jost,sans-serif;">Naghahanap...</div>';
          dropdown.style.display = 'block';
          debounceTimer = setTimeout(function () {
            const url = new URL(window.location.href);
            url.searchParams.set('search', val);
            url.searchParams.set('suggest', '1');
            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
              .then(r => r.json())
              .then(function (data) {
                if (!data.length) {
                  dropdown.innerHTML = '<div style="padding:10px 14px;font-size:12px;color:rgba(74,46,26,0.4);font-family:Jost,sans-serif;">Walang nahanap</div>';
                  return;
                }
                dropdown.innerHTML = data.map(function (item) {
                  return '<div class="suggest-item" style="padding:10px 14px;font-size:13px;color:#2c1a0e;font-family:Jost,sans-serif;cursor:pointer;border-bottom:1px solid rgba(184,146,74,0.08);" onmouseover="this.style.background=\'rgba(184,146,74,0.08)\'" onmouseout="this.style.background=\'\'">'
                    + '<span style="color:#b8924a;margin-right:6px;">🔍</span>' + item + '</div>';
                }).join('');
                dropdown.querySelectorAll('.suggest-item').forEach(function (el) {
                  el.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    input.value = el.textContent.replace('🔍', '').trim();
                    dropdown.style.display = 'none';
                    form.submit();
                  });
                });
              })
              .catch(function () { form.submit(); });
            form.submit();
          }, 500);
        });
        input.addEventListener('blur', function () { setTimeout(() => dropdown.style.display = 'none', 200); });
        input.addEventListener('focus', function () { if (input.value.trim().length > 0 && dropdown.innerHTML) dropdown.style.display = 'block'; });
        input.addEventListener('keydown', function (e) {
          if (e.key === 'Enter') { e.preventDefault(); form.submit(); }
          if (e.key === 'Escape') dropdown.style.display = 'none';
        });
      });
    });
  </script>
</body>
</html>
