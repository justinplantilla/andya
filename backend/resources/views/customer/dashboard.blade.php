@extends('customer.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Maligayang pagbabalik')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-gold/15"><svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Total</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $total_orders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Lahat ng Orders</div>
  </div>
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-gold/15"><svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Active</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $pending_orders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Pending Orders</div>
  </div>
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-sage/15"><svg class="w-5 h-5 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Done</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $delivered_orders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Delivered</div>
  </div>
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-rust/10"><svg class="w-5 h-5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Void</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $cancelled_orders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Cancelled</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-display text-xl text-bark font-medium">Mga Kamakailang Order</h2>
      <a href="{{ route('customer.orders') }}" class="text-xs text-gold hover:text-rust transition-colors tracking-wide">Tingnan Lahat →</a>
    </div>
    @if($recent_orders->isEmpty())
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-bark-mid/50 text-sm">Wala pang mga order.</p>
        <a href="{{ route('customer.products') }}" class="mt-2 text-xs text-gold hover:text-rust transition-colors">Mag-order na →</a>
      </div>
    @else
      <table class="w-full">
        <thead>
          <tr class="border-b border-gold/15">
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Order #</th>
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Total</th>
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Status</th>
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recent_orders as $order)
          <tr class="table-row">
            <td class="py-3 px-3 text-sm text-bark font-medium">{{ $order->order_number }}</td>
            <td class="py-3 px-3 text-sm text-bark">₱{{ number_format($order->total_amount, 2) }}</td>
            <td class="py-3 px-3"><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
            <td class="py-3 px-3 text-xs text-bark-mid/50">{{ $order->created_at->format('M d, Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-display text-xl text-bark font-medium">Mga Produkto</h2>
      <a href="{{ route('customer.products') }}" class="text-xs text-gold hover:text-rust transition-colors tracking-wide">Lahat →</a>
    </div>
    @if($featured_products->isEmpty())
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <p class="text-bark-mid/50 text-sm">Wala pang mga produkto.</p>
      </div>
    @else
      <div class="flex flex-col gap-3">
        @foreach($featured_products as $product)
        <div class="flex items-center justify-between py-2 border-b border-gold/10">
          <div>
            <div class="text-sm text-bark font-medium">{{ $product->name }}</div>
            <div class="text-xs text-bark-mid/50">{{ $product->category->name }}</div>
          </div>
          <div class="text-sm text-gold font-medium">₱{{ number_format($product->price, 2) }}</div>
        </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
