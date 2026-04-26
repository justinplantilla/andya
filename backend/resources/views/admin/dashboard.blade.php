@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview ng sistema')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-gold/15">
        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Total</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $total_products }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Products</div>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-gold/15">
        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Active</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $pending_orders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Pending Orders</div>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-sage/15">
        <svg class="w-5 h-5 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Users</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">{{ $total_customers }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">Customers</div>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-rust/10">
        <svg class="w-5 h-5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Revenue</span>
    </div>
    <div class="font-sans text-4xl text-bark font-semibold">₱{{ number_format($monthly_revenue, 2) }}</div>
    <div class="text-bark-mid/60 text-xs mt-1 tracking-wide">This Month</div>
  </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- Recent Orders -->
  <div class="lg:col-span-2 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-display text-xl text-bark font-medium">Mga Kamakailang Order</h2>
      <a href="{{ route('admin.orders.index') }}" class="text-xs text-gold hover:text-rust transition-colors tracking-wide">Tingnan Lahat →</a>
    </div>

    @if($recent_orders->isEmpty())
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-bark-mid/50 text-sm">Wala pang mga order.</p>
      </div>
    @else
      <table class="w-full">
        <thead>
          <tr class="border-b border-gold/15">
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Order #</th>
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Customer</th>
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Total</th>
            <th class="text-left py-2 px-3 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recent_orders as $order)
          <tr class="table-row">
            <td class="py-3 px-3 text-sm text-bark font-medium">{{ $order->order_number }}</td>
            <td class="py-3 px-3 text-sm text-bark-mid/70">{{ $order->user->name }}</td>
            <td class="py-3 px-3 text-sm text-bark">₱{{ number_format($order->total_amount, 2) }}</td>
            <td class="py-3 px-3">
              <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <!-- Low Stock -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-display text-xl text-bark font-medium">Low Stock</h2>
      <a href="{{ route('admin.inventory.index') }}" class="text-xs text-gold hover:text-rust transition-colors tracking-wide">Lahat →</a>
    </div>

    @if($low_stock->isEmpty())
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        </div>
        <p class="text-bark-mid/50 text-sm">Lahat ng stock ay sapat.</p>
      </div>
    @else
      <div class="flex flex-col gap-3">
        @foreach($low_stock as $item)
        <div class="flex items-center justify-between py-2 border-b border-gold/10">
          <span class="text-sm text-bark">{{ $item->product->name }}</span>
          <span class="badge badge-pending">{{ $item->quantity }} left</span>
        </div>
        @endforeach
      </div>
    @endif
  </div>

</div>
@endsection
