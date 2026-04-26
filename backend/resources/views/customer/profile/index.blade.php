@extends('customer.layout')
@section('title', 'Profile')
@section('page-title', 'Profile')
@section('page-subtitle', 'I-manage ang iyong account')

@section('content')
<div class="max-w-2xl flex flex-col gap-6">

  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Personal Information</h2>
    <form method="POST" action="{{ route('customer.profile.update') }}" class="flex flex-col gap-4">
      @csrf
      @method('PUT')
      @if(session('success'))
        <div class="text-sage text-sm bg-sage/10 py-3 px-4 rounded">{{ session('success') }}</div>
      @endif
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Buong Pangalan</label>
        <input type="text" name="name" class="input-field" value="{{ Auth::user()->name }}" required/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Email</label>
        <input type="email" name="email" class="input-field" value="{{ Auth::user()->email }}" required/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Default Address</label>
        <textarea name="address" rows="3" class="input-field resize-none" placeholder="Ilagay ang iyong default address...">{{ Auth::user()->address }}</textarea>
        <p class="text-xs text-bark-mid/40">Ito ang awtomatikong lalabas sa checkout.</p>
      </div>
      <button type="submit" class="btn-gold w-fit">Save Changes</button>
    </form>
  </div>

  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Change Password</h2>
    <form method="POST" action="{{ route('customer.profile.password') }}" class="flex flex-col gap-4">
      @csrf
      @method('PUT')
      @if($errors->any())
        <div class="text-rust text-sm bg-rust/10 py-3 px-4 rounded">{{ $errors->first() }}</div>
      @endif
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
