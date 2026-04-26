@extends('customer.layout')
@section('title', 'Order Confirmed')
@section('page-title', 'Order Confirmed')
@section('page-subtitle', 'Salamat sa iyong order!')

@section('content')
<div class="max-w-2xl mx-auto">

  <!-- Success Banner -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-8 mb-6 text-center relative overflow-hidden">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <div class="w-16 h-16 rounded-full bg-sage/15 flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1 class="font-display text-3xl text-bark font-medium mb-2">Order Placed!</h1>
    <p class="text-bark-mid/60 text-sm">Ang iyong order ay natanggap na. Abangan ang iyong delivery!</p>
  </div>

  <!-- Order Details -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6 mb-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Order Details</h2>

    <div class="grid grid-cols-2 gap-4 mb-5">
      <div class="flex flex-col gap-1">
        <span class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Order Number</span>
        <span class="text-sm text-bark font-semibold">{{ $order->order_number }}</span>
      </div>
      <div class="flex flex-col gap-1">
        <span class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Invoice Number</span>
        <span class="text-sm text-bark font-semibold">{{ $order->invoice->invoice_number ?? '—' }}</span>
      </div>
      <div class="flex flex-col gap-1">
        <span class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Payment Method</span>
        <div class="flex items-center gap-2">
          @if($order->payment_method === 'gcash')
            <span class="w-6 h-6 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 font-bold text-[10px]">G</span>
            <span class="text-sm text-bark font-medium">GCash</span>
            @if($order->gcash_number)
              <span class="text-xs text-bark-mid/50">({{ $order->gcash_number }})</span>
            @endif
          @else
            <svg class="w-4 h-4 text-bark-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="text-sm text-bark font-medium">Cash on Delivery</span>
          @endif
        </div>
      </div>
      <div class="flex flex-col gap-1">
        <span class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Status</span>
        <span class="badge badge-pending w-fit">{{ ucfirst($order->status) }}</span>
      </div>
      <div class="flex flex-col gap-1">
        <span class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Date</span>
        <span class="text-sm text-bark">{{ $order->created_at->format('M d, Y h:i A') }}</span>
      </div>
    </div>

    <!-- Items -->
    <div class="border-t border-gold/10 pt-4">
      <h3 class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium mb-3">Items Ordered</h3>
      <div class="flex flex-col gap-3">
        @foreach($order->orderItems as $item)
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-lg bg-bark/5 flex-shrink-0 overflow-hidden flex items-center justify-center">
            @if($item->product->image)
              <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover"/>
            @else
              <svg class="w-5 h-5 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
          </div>
          <div class="flex-1">
            <div class="text-sm text-bark font-medium">{{ $item->product->name }}</div>
            <div class="text-xs text-bark-mid/50">{{ $item->quantity }} {{ $item->product->unit }} × ₱{{ number_format($item->unit_price, 2) }}</div>
          </div>
          <div class="text-sm text-bark font-semibold">₱{{ number_format($item->total_price, 2) }}</div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Total -->
    <div class="border-t border-gold/10 mt-4 pt-4 flex justify-between items-center">
      <span class="text-sm text-bark font-semibold">Total Amount</span>
      <span class="font-sans text-xl text-bark font-semibold">₱{{ number_format($order->total_amount, 2) }}</span>
    </div>
  </div>

  <!-- Shipping Address -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6 mb-6">
    <h2 class="font-display text-xl text-bark font-medium mb-3">Shipping Address</h2>
    <div class="flex items-start gap-3">
      <svg class="w-4 h-4 text-gold mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <p class="text-sm text-bark leading-relaxed">{{ $order->shipping_address }}</p>
    </div>
  </div>

  <!-- Actions -->
  <div class="flex gap-3">
    <a href="{{ route('customer.orders') }}" class="btn-gold flex-1 justify-center">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      View My Orders
    </a>
    <a href="{{ route('customer.products') }}" class="btn-outline flex-1 justify-center">
      Continue Shopping
    </a>
  </div>

</div>
@endsection
