@extends('admin.layout')
@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Pamahalaan ang mga order ng customers')

@section('content')

<!-- Filters -->
<form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-3 mb-6">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="Maghanap ng order #..." class="input-field w-64"/>
  <select name="status" class="input-field w-44" onchange="this.form.submit()">
    <option value="">All Status</option>
    <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>Pending</option>
    <option value="confirmed"  {{ request('status') === 'confirmed'  ? 'selected' : '' }}>Confirmed</option>
    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
    <option value="shipped"    {{ request('status') === 'shipped'    ? 'selected' : '' }}>Shipped</option>
    <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>Delivered</option>
    <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
  </select>
  <select name="payment_method" class="input-field w-40" onchange="this.form.submit()">
    <option value="">All Payment</option>
    <option value="cod"   {{ request('payment_method') === 'cod'   ? 'selected' : '' }}>Cash on Delivery</option>
    <option value="gcash" {{ request('payment_method') === 'gcash' ? 'selected' : '' }}>GCash</option>
  </select>
  @if(request('search') || request('status') || request('payment_method'))
    <a href="{{ route('admin.orders.index') }}" class="btn-outline">Clear</a>
  @endif
</form>

@if($orders->isEmpty())
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex flex-col items-center justify-center py-16 text-center">
      <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mb-4">
        <svg class="w-6 h-6 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <p class="text-bark-mid/50 text-sm">Walang nahanap na order.</p>
    </div>
  </div>
@else
  <div class="flex flex-col gap-4">
    @foreach($orders as $order)
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 overflow-hidden">

      <!-- Order Header -->
      <div class="flex items-center justify-between px-5 py-3 border-b border-gold/10">
        <div class="flex items-center gap-4">
          <span class="text-sm font-semibold text-bark">{{ $order->order_number }}</span>
          <span class="text-sm text-bark-mid/50">{{ $order->created_at->format('M d, Y h:i A') }}</span>
        </div>
      </div>

      <!-- Customer + Payment Info -->
      <div class="flex items-center gap-6 px-5 py-2.5 border-b border-gold/10 bg-bark/[0.02]">
        <div class="flex items-center gap-2 text-sm text-bark-mid/70">
          <svg class="w-4 h-4 text-bark-mid/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          {{ $order->user->name }}
        </div>
        <div class="flex items-center gap-2 text-sm text-bark-mid/70">
          <svg class="w-4 h-4 text-bark-mid/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          {{ $order->shipping_address }}
        </div>
        <div class="flex items-center gap-1.5 text-sm text-bark-mid/70 ml-auto">
          @if($order->payment_method === 'gcash')
            <span class="w-5 h-5 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 font-bold text-[9px]">G</span>
            <span>GCash{{ $order->gcash_number ? ' — '.$order->gcash_number : '' }}</span>
            @if($order->gcash_screenshot)
              <a href="{{ asset('storage/'.$order->gcash_screenshot) }}" target="_blank"
                class="ml-2 text-xs text-blue-600 hover:underline flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                View Screenshot
              </a>
            @else
              <span class="ml-2 text-xs text-rust/60">No screenshot</span>
            @endif
          @else
            <svg class="w-4 h-4 text-bark-mid/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Cash on Delivery</span>
          @endif
        </div>
      </div>

      <!-- Items -->
      <div class="px-5 py-2 flex flex-col divide-y divide-gold/10">
        @foreach($order->orderItems as $item)
        <div class="flex items-center gap-4 py-3">
          <div class="w-12 h-12 rounded-xl bg-bark/5 flex-shrink-0 overflow-hidden flex items-center justify-center">
            @if($item->product->image)
              <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover"/>
            @else
              <svg class="w-5 h-5 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-sm font-medium text-bark">{{ $item->product->name }}</div>
            <div class="text-xs text-bark-mid/50 mt-0.5">{{ $item->product->category->name }}</div>
            <div class="text-sm text-bark-mid/60 mt-1">
              x{{ $item->quantity }} {{ $item->product->unit }}
              <span class="mx-1 text-bark-mid/30">·</span>
              ₱{{ number_format($item->unit_price, 2) }} each
            </div>
          </div>
          <div class="text-sm font-semibold text-bark flex-shrink-0">₱{{ number_format($item->total_price, 2) }}</div>
        </div>
        @endforeach
      </div>

      <!-- Order Footer -->
      @php
        $steps = ['pending','confirmed','processing','shipped','delivered'];
        $current = $order->status;
        $currentIdx = array_search($current, $steps);
        $nextStatus = ($currentIdx !== false && $currentIdx < count($steps)-1) ? $steps[$currentIdx+1] : null;
        $statusColors = [
          'pending'    => 'bg-gold/15 text-gold border-gold/30',
          'confirmed'  => 'bg-blue-500/10 text-blue-600 border-blue-200',
          'processing' => 'bg-purple-500/10 text-purple-600 border-purple-200',
          'shipped'    => 'bg-sky-500/10 text-sky-600 border-sky-200',
          'delivered'  => 'bg-sage/15 text-sage border-sage/30',
          'cancelled'  => 'bg-rust/10 text-rust border-rust/20',
        ];
        $nextLabels = [
          'pending'    => 'Confirm',
          'confirmed'  => 'Process',
          'processing' => 'Ship',
          'shipped'    => 'Delivered',
        ];
      @endphp
      <div class="flex items-center justify-between px-5 py-3 border-t border-gold/10">

        {{-- Left: Status pills --}}
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium {{ $statusColors[$current] ?? 'bg-bark/5 text-bark border-bark/10' }}">
            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
            {{ ucfirst($current) }}
          </span>
          @if($nextStatus)
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
              @csrf @method('PUT')
              <input type="hidden" name="status" value="{{ $nextStatus }}"/>
              <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gold/30 bg-gold/8 text-bark-mid text-xs font-medium hover:bg-gold/20 transition-all">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                {{ $nextLabels[$current] ?? '' }}
              </button>
            </form>
          @endif
          @if(!in_array($current, ['delivered','cancelled']))
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
              @csrf @method('PUT')
              <input type="hidden" name="status" value="cancelled"/>
              <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-rust/20 bg-rust/5 text-rust text-xs font-medium hover:bg-rust/15 transition-all">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
              </button>
            </form>
          @endif
        </div>

        {{-- Right: Print + Total --}}
        <div class="flex items-center gap-4">
          @if($current !== 'cancelled')
            @if($current === 'pending')
              <form method="POST" action="{{ route('admin.orders.processPrint', $order) }}" target="_blank">
                @csrf
                <button type="submit" class="btn-gold py-2 px-4 text-xs flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                  Confirm & Print
                </button>
              </form>
            @else
              <a href="{{ route('admin.invoices.receipt', $order->invoice) }}" target="_blank" class="btn-outline py-2 px-4 text-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Receipt
              </a>
            @endif
          @endif
          <div class="text-right">
            <div class="text-xs text-bark-mid/50">Order Total</div>
            <div class="font-sans text-lg text-bark font-semibold">₱{{ number_format($order->total_amount, 2) }}</div>
          </div>
        </div>

      </div>

    </div>
    @endforeach
  </div>

  <div class="mt-4">{{ $orders->appends(request()->query())->links() }}</div>
@endif
@endsection
