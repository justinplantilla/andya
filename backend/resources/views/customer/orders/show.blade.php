@extends('customer.layout')
@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'Detalye ng iyong order')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif

<div class="max-w-2xl flex flex-col gap-6">

  <!-- Order Status Card -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6 relative overflow-hidden">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <div class="flex items-start justify-between mb-5">
      <div>
        <div class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium mb-1">Order Number</div>
        <div class="font-sans text-lg text-bark font-semibold">{{ $order->order_number }}</div>
      </div>
      <span class="badge badge-{{ $order->status }} text-sm px-4 py-1.5">{{ ucfirst($order->status) }}</span>
    </div>

    <!-- Status Timeline -->
    <div class="flex items-center mb-5">
      @php
        $steps = ['pending'=>0,'confirmed'=>1,'processing'=>2,'shipped'=>3,'delivered'=>4];
        $currentStep = $order->status === 'cancelled' ? -1 : ($steps[$order->status] ?? 0);
        $labels = ['Pending','Confirmed','Processing','Shipped','Delivered'];
      @endphp

      @if($order->status === 'cancelled')
        <div class="flex items-center gap-2 text-rust/70">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          <span class="text-sm font-medium">Order Cancelled</span>
        </div>
      @else
        @foreach($labels as $i => $label)
          <div class="flex items-center {{ $i < 4 ? 'flex-1' : '' }}">
            <div class="flex flex-col items-center">
              <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold
                {{ $i <= $currentStep ? 'bg-gold text-bark' : 'bg-bark/10 text-bark-mid/40' }}">
                @if($i < $currentStep)
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @else
                  {{ $i + 1 }}
                @endif
              </div>
              <span class="text-[9px] mt-1 tracking-wide text-center {{ $i <= $currentStep ? 'text-gold' : 'text-bark-mid/40' }}">{{ $label }}</span>
            </div>
            @if($i < 4)
              <div class="flex-1 h-0.5 mx-1 {{ $i < $currentStep ? 'bg-gold' : 'bg-bark/10' }}"></div>
            @endif
          </div>
        @endforeach
      @endif
    </div>

    <div class="grid grid-cols-2 gap-4 text-sm">
      <div>
        <div class="text-xs text-bark-mid/50 mb-0.5">Invoice #</div>
        <div class="text-bark font-medium">{{ $order->invoice->invoice_number ?? '—' }}</div>
      </div>
      <div>
        <div class="text-xs text-bark-mid/50 mb-0.5">Payment Method</div>
        <div class="flex items-center gap-1.5">
          @if($order->payment_method === 'gcash')
            <span class="w-5 h-5 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 font-bold text-[9px]">G</span>
            <span class="text-sm text-bark">GCash {{ $order->gcash_number ? '('.$order->gcash_number.')' : '' }}</span>
          @else
            <svg class="w-4 h-4 text-bark-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="text-sm text-bark">Cash on Delivery</span>
          @endif
        </div>
      </div>
      <div>
        <div class="text-xs text-bark-mid/50 mb-0.5">Payment</div>
        <div>
          <span class="badge badge-{{ $order->invoice->status === 'paid' ? 'delivered' : 'pending' }}">
            {{ ucfirst($order->invoice->status ?? 'unpaid') }}
          </span>
        </div>
      </div>
      <div>
        <div class="text-xs text-bark-mid/50 mb-0.5">Order Date</div>
        <div class="text-bark">{{ $order->created_at->format('M d, Y h:i A') }}</div>
      </div>
      <div>
        <div class="text-xs text-bark-mid/50 mb-0.5">Total Amount</div>
        <div class="text-bark font-semibold">₱{{ number_format($order->total_amount, 2) }}</div>
      </div>
    </div>
  </div>

  <!-- Items -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-4">Items Ordered</h2>
    <div class="flex flex-col gap-4">
      @foreach($order->orderItems as $item)
      <div class="flex items-center gap-4 pb-4 border-b border-gold/10 last:border-0 last:pb-0">
        <div class="w-14 h-14 rounded-xl bg-bark/5 flex-shrink-0 overflow-hidden flex items-center justify-center">
          @if($item->product->image)
            <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover"/>
          @else
            <svg class="w-6 h-6 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          @endif
        </div>
        <div class="flex-1">
          <div class="text-sm text-bark font-medium">{{ $item->product->name }}</div>
          <div class="text-xs text-bark-mid/50 mt-0.5">{{ $item->product->category->name }}</div>
          <div class="text-xs text-bark-mid/60 mt-1">{{ $item->quantity }} {{ $item->product->unit }} × ₱{{ number_format($item->unit_price, 2) }}</div>
        </div>
        <div class="text-sm text-bark font-semibold">₱{{ number_format($item->total_price, 2) }}</div>
      </div>
      @endforeach
    </div>

    <div class="border-t border-gold/10 mt-4 pt-4 flex justify-between">
      <span class="text-sm text-bark font-semibold">Total</span>
      <span class="font-sans text-xl text-bark font-semibold">₱{{ number_format($order->total_amount, 2) }}</span>
    </div>
  </div>

  <!-- Shipping Address -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-3">Shipping Address</h2>
    <div class="flex items-start gap-3">
      <svg class="w-4 h-4 text-gold mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <p class="text-sm text-bark leading-relaxed">{{ $order->shipping_address }}</p>
    </div>
  </div>

  <!-- Actions -->
  <div class="flex gap-3">
    <a href="{{ route('customer.orders') }}" class="btn-outline flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Orders
    </a>

    @if($order->status === 'pending')
      <button type="button" onclick="document.getElementById('cancel-modal').classList.remove('hidden'); document.getElementById('cancel-modal').classList.add('flex'); document.body.style.overflow='hidden';"
        class="flex items-center gap-2 bg-rust/10 text-rust text-sm font-medium py-2.5 px-5 rounded-md hover:bg-rust/20 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Cancel Order
      </button>
    @endif

    <a href="{{ route('customer.products') }}" class="btn-gold ml-auto flex items-center gap-2">
      Continue Shopping
    </a>
  </div>
</div>

<!-- Cancel Modal -->
<div id="cancel-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden'); this.parentElement.classList.remove('flex'); document.body.style.overflow='';"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-sm z-10 p-6 text-center">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <div class="w-14 h-14 rounded-full bg-rust/10 flex items-center justify-center mx-auto mb-4">
      <svg class="w-7 h-7 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <h2 class="font-display text-xl text-bark font-medium mb-2">I-cancel ang Order?</h2>
    <p class="text-bark font-semibold text-sm mb-1">{{ $order->order_number }}</p>
    <p class="text-bark-mid/50 text-xs mb-6">Hindi na ito mababago pagkatapos ma-cancel.</p>
    <form method="POST" action="{{ route('customer.orders.cancel', $order->id) }}">
      @csrf @method('PUT')
      <div class="flex gap-3">
        <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden'); document.getElementById('cancel-modal').classList.remove('flex'); document.body.style.overflow='';" class="btn-outline flex-1 justify-center">Bumalik</button>
        <button type="submit" class="flex-1 bg-rust text-cream text-sm font-medium py-2.5 px-4 rounded-md hover:bg-rust/80 transition-colors">I-cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection
