@extends('customer.layout')
@section('title', 'Profile')
@section('page-title', 'Profile')
@section('page-subtitle', 'I-manage ang iyong account')

@section('content')
@php
  $user        = Auth::user();
  $totalOrders = \App\Models\Order::where('user_id', $user->id)->count();
  $delivered   = \App\Models\Order::where('user_id', $user->id)->where('status', 'delivered')->count();
  $pending     = \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count();
  $cancelled   = \App\Models\Order::where('user_id', $user->id)->where('status', 'cancelled')->count();
@endphp

<div class="max-w-2xl mx-auto flex flex-col gap-5">

  {{-- Greeting --}}
  <div class="flex items-center gap-4 px-1">
    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-gold/40 to-bark-mid flex items-center justify-center text-cream font-display text-2xl font-medium flex-shrink-0">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div>
      <p class="text-xs text-bark-mid/50">Kamusta,</p>
      <h2 class="font-display text-2xl text-bark font-medium leading-tight">{{ $user->name }} 👋</h2>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-4 gap-3">
    @foreach([
      ['label' => 'Total',     'value' => $totalOrders, 'color' => 'text-bark'],
      ['label' => 'Delivered', 'value' => $delivered,   'color' => 'text-sage'],
      ['label' => 'Pending',   'value' => $pending,     'color' => 'text-gold'],
      ['label' => 'Cancelled', 'value' => $cancelled,   'color' => 'text-rust'],
    ] as $stat)
    <div class="bg-white/60 border border-gold/10 rounded-xl py-4 text-center">
      <div class="font-display text-2xl font-medium {{ $stat['color'] }}">{{ $stat['value'] }}</div>
      <div class="text-[10px] text-bark-mid/50 mt-0.5">{{ $stat['label'] }}</div>
    </div>
    @endforeach
  </div>

  {{-- Personal Information --}}
  <div class="bg-white/60 border border-gold/10 rounded-2xl p-6">
    <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium mb-4">Personal Information</p>
    <form method="POST" action="{{ route('customer.profile.update') }}" class="flex flex-col gap-4">
      @csrf @method('PUT')
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Buong Pangalan</label>
          <input type="text" name="name" class="input-field" value="{{ $user->name }}" required/>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Email</label>
          <input type="email" name="email" class="input-field" value="{{ $user->email }}" required/>
        </div>
      </div>
      <div class="flex flex-col gap-1.5">
        <label class="text-xs text-bark-mid/60">Default Address</label>
        <textarea name="address" rows="2" class="input-field resize-none" placeholder="Ilagay ang iyong address...">{{ $user->address }}</textarea>
        <p class="text-[11px] text-bark-mid/40">Awtomatikong lalabas ito sa checkout.</p>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="btn-gold">I-save</button>
      </div>
    </form>
  </div>

  {{-- Change Password --}}
  <div class="bg-white/60 border border-gold/10 rounded-2xl p-6">
    <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium mb-4">Baguhin ang Password</p>
    <form method="POST" action="{{ route('customer.profile.password') }}" class="flex flex-col gap-4">
      @csrf @method('PUT')
      <div class="flex flex-col gap-1.5">
        <label class="text-xs text-bark-mid/60">Kasalukuyang Password</label>
        <div class="relative">
          <input type="password" name="current_password" id="pw-current" class="input-field pr-10" placeholder="••••••••"/>
          <button type="button" onclick="togglePw('pw-current','eye-current')" class="absolute right-3 top-1/2 -translate-y-1/2 text-bark/30 hover:text-bark transition-colors">
            <svg id="eye-current" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </button>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Bagong Password</label>
          <div class="relative">
            <input type="password" name="password" id="pw-new" class="input-field pr-10" placeholder="••••••••"/>
            <button type="button" onclick="togglePw('pw-new','eye-new')" class="absolute right-3 top-1/2 -translate-y-1/2 text-bark/30 hover:text-bark transition-colors">
              <svg id="eye-new" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
          </div>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Kumpirmahin</label>
          <div class="relative">
            <input type="password" name="password_confirmation" id="pw-confirm" class="input-field pr-10" placeholder="••••••••"/>
            <button type="button" onclick="togglePw('pw-confirm','eye-confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-bark/30 hover:text-bark transition-colors">
              <svg id="eye-confirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
          </div>
        </div>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="btn-gold">I-update</button>
      </div>
    </form>
  </div>

</div>

<script>
  function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    const icon = document.getElementById(iconId);
    icon.innerHTML = isText
      ? '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
      : '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
  }
</script>
@endsection
