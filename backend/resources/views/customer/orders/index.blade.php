@extends('customer.layout')
@section('title', 'My Orders')
@section('page-title', 'My Orders')
@section('page-subtitle', 'Lahat ng iyong mga order')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="mb-4 text-rust text-sm bg-rust/10 py-3 px-4 rounded-lg">{{ session('error') }}</div>
@endif

<!-- Filters -->
<form method="GET" action="{{ route('customer.orders') }}" class="flex items-center gap-3 mb-6">
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
  @if(request('search') || request('status'))
    <a href="{{ route('customer.orders') }}" class="btn-outline">Clear</a>
  @endif
</form>

@if($orders->isEmpty())
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex flex-col items-center justify-center py-16 text-center">
      <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mb-4">
        <svg class="w-6 h-6 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <p class="text-bark-mid/50 text-sm mb-3">
        {{ request('search') || request('status') ? 'Walang nahanap na order.' : 'Wala pang mga order.' }}
      </p>
      @if(request('search') || request('status'))
        <a href="{{ route('customer.orders') }}" class="text-xs text-gold hover:text-rust">Tingnan lahat →</a>
      @else
        <a href="{{ route('customer.products') }}" class="btn-gold">Mag-order na</a>
      @endif
    </div>
  </div>
@else
  <div class="flex flex-col gap-4">
    @foreach($orders as $order)
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 overflow-hidden">

      <!-- Order Header -->
      <div class="flex items-center justify-between px-5 py-3 border-b border-gold/10 bg-bark/3">
        <div class="flex items-center gap-4">
          <span class="text-xs text-bark-mid/50 tracking-widest uppercase font-medium">{{ $order->order_number }}</span>
          <span class="text-xs text-bark-mid/40">{{ $order->created_at->format('M d, Y') }}</span>
        </div>
        <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
      </div>

      <!-- Items List -->
      <div class="px-5 py-3 flex flex-col divide-y divide-gold/10">
        @foreach($order->orderItems as $item)
        <div class="flex items-center gap-4 py-3">
          <!-- Product Image -->
          <div class="w-14 h-14 rounded-xl bg-bark/5 flex-shrink-0 overflow-hidden flex items-center justify-center">
            @if($item->product->image)
              <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover"/>
            @else
              <svg class="w-6 h-6 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
          </div>

          <!-- Product Info -->
          <div class="flex-1 min-w-0">
            <div class="text-sm text-bark font-medium">{{ $item->product->name }}</div>
            <div class="text-xs text-bark-mid/50 mt-0.5">{{ $item->product->category->name }}</div>
            <div class="text-xs text-bark-mid/60 mt-1">
              x{{ $item->quantity }} {{ $item->product->unit }}
              <span class="mx-1 text-bark-mid/30">·</span>
              ₱{{ number_format($item->unit_price, 2) }} each
            </div>
          </div>

          <!-- Item Total -->
          <div class="text-sm text-bark font-semibold flex-shrink-0">₱{{ number_format($item->total_price, 2) }}</div>
        </div>
        @endforeach
      </div>

      <!-- Order Footer -->
      <div class="flex items-center justify-between px-5 py-3 border-t border-gold/10 bg-bark/3">
        <div class="flex items-center gap-4 text-xs text-bark-mid/50">
          <!-- Payment Method -->
          <div class="flex items-center gap-1.5">
            @if($order->payment_method === 'gcash')
              <span class="w-4 h-4 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 font-bold text-[8px]">G</span>
              <span>GCash</span>
            @else
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <span>Cash on Delivery</span>
            @endif
          </div>

        </div>

        <div class="flex items-center gap-4">
          <div class="text-right">
            <div class="text-xs text-bark-mid/50">Order Total</div>
            <div class="font-sans text-base text-bark font-semibold">₱{{ number_format($order->total_amount, 2) }}</div>
          </div>

          <div class="flex items-center gap-2">
            <a href="{{ route('customer.orders.show', $order->id) }}"
              class="text-xs text-gold hover:text-rust transition-colors tracking-wide flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              View Details
            </a>
            @if($order->status === 'pending')
              <button type="button"
                onclick="confirmCancel({{ $order->id }}, '{{ $order->order_number }}')"
                class="text-xs text-rust/60 hover:text-rust transition-colors tracking-wide flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
              </button>
            @endif
          </div>
        </div>
      </div>

    </div>
    @endforeach
  </div>

  <div class="mt-4">{{ $orders->appends(request()->query())->links() }}</div>
@endif

<!-- Cancel Confirmation Modal -->
<div id="cancel-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeCancelModal()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-sm z-10 p-6 text-center">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <div class="w-14 h-14 rounded-full bg-rust/10 flex items-center justify-center mx-auto mb-4">
      <svg class="w-7 h-7 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>

    <h2 class="font-display text-xl text-bark font-medium mb-2">I-cancel ang Order?</h2>
    <p class="text-bark-mid/60 text-sm mb-1">Order Number:</p>
    <p class="text-bark font-semibold text-sm mb-4" id="cancel-order-number"></p>
    <p class="text-bark-mid/50 text-xs mb-6">Hindi na ito mababago pagkatapos ma-cancel.</p>

    <form method="POST" id="cancel-form" action="">
      @csrf
      @method('PUT')
      <div class="flex gap-3">
        <button type="button" onclick="closeCancelModal()" class="btn-outline flex-1 justify-center">Bumalik</button>
        <button type="submit" class="flex-1 bg-rust text-cream text-sm font-medium py-2.5 px-4 rounded-md hover:bg-rust/80 transition-colors flex items-center justify-center gap-1.5">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          I-cancel
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function confirmCancel(id, orderNumber) {
    document.getElementById('cancel-order-number').textContent = orderNumber;
    document.getElementById('cancel-form').action = '/customer/orders/' + id + '/cancel';
    const modal = document.getElementById('cancel-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function closeCancelModal() {
    const modal = document.getElementById('cancel-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCancelModal(); });
</script>
@endsection
