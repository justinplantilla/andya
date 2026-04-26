@extends('admin.layout')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'I-configure ang sistema')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif
@if(session('password_success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('password_success') }}</div>
@endif
@if($errors->has('current_password'))
  <div class="mb-4 text-rust text-sm bg-rust/10 py-3 px-4 rounded-lg">{{ $errors->first('current_password') }}</div>
@endif

<div class="max-w-2xl flex flex-col gap-6">

  <!-- Store Info + GCash -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Store Information</h2>
    <form method="POST" action="{{ route('admin.settings.save') }}" class="flex flex-col gap-4">
      @csrf
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Store Name</label>
        <input type="text" name="store_name" class="input-field" value="{{ $store_name }}"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Email</label>
        <input type="email" name="store_email" class="input-field" value="{{ $store_email }}" placeholder="store@email.com"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Phone</label>
        <input type="text" name="store_phone" class="input-field" value="{{ $store_phone }}" placeholder="+63 9XX XXX XXXX"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Address</label>
        <textarea name="store_address" rows="2" class="input-field resize-none" placeholder="Store address...">{{ $store_address }}</textarea>
      </div>

      <div class="border-t border-gold/15 pt-4 flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">GCash Number</label>
        <p class="text-xs text-bark-mid/40">Ito ang ipapakita sa customers kapag pumili sila ng GCash payment.</p>
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-blue-500/10 flex items-center justify-center flex-shrink-0">
            <span class="text-blue-600 font-bold text-sm">G</span>
          </div>
          <input type="text" name="gcash_number" class="input-field" value="{{ $gcash_number }}" placeholder="09XX XXX XXXX" maxlength="11"/>
        </div>
      </div>

      <button type="submit" class="btn-gold w-fit">Save Changes</button>
    </form>
  </div>

  <!-- Change Password -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Change Password</h2>
    <form method="POST" action="{{ route('admin.profile.password') }}" class="flex flex-col gap-4">
      @csrf @method('PUT')
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Current Password</label>
        <input type="password" name="current_password" class="input-field" placeholder="••••••••"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">New Password</label>
        <input type="password" name="password" class="input-field" placeholder="••••••••"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••"/>
      </div>
      <button type="submit" class="btn-gold w-fit">Update Password</button>
    </form>
  </div>

</div>
@endsection
