@extends('admin.layout')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'I-configure ang sistema')

@section('content')
<div class="max-w-2xl mx-auto flex flex-col gap-5">

  {{-- Store Information --}}
  <div class="bg-white/60 border border-gold/10 rounded-2xl p-6 flex flex-col gap-4">
    <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Store Information</p>
    <form method="POST" action="{{ route('admin.settings.save') }}" class="flex flex-col gap-4">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Store Name</label>
          <input type="text" name="store_name" class="input-field" value="{{ $store_name }}"/>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Email</label>
          <input type="email" name="store_email" class="input-field" value="{{ $store_email }}" placeholder="store@email.com"/>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Phone</label>
          <input type="text" name="store_phone" class="input-field" value="{{ $store_phone }}" placeholder="+63 9XX XXX XXXX"/>
        </div>
        <div class="flex flex-col gap-1.5 sm:col-span-2">
          <label class="text-xs text-bark-mid/60">Address</label>
          <textarea name="store_address" rows="2" class="input-field resize-none" placeholder="Store address...">{{ $store_address }}</textarea>
        </div>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="btn-gold">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          I-save
        </button>
      </div>
    </form>
  </div>

  {{-- Change Password --}}
  <div class="bg-white/60 border border-gold/10 rounded-2xl p-6 flex flex-col gap-4">
    <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Baguhin ang Password</p>
    <form method="POST" action="{{ route('admin.profile.password') }}" class="flex flex-col gap-4">
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
        <button type="submit" class="btn-gold">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          I-update
        </button>
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
