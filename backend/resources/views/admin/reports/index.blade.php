@extends('admin.layout')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Sales at performance overview')

@section('content')
@php
  $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
@endphp

<!-- Filter -->
<form method="GET" action="{{ route('admin.reports') }}" class="flex items-center gap-3 mb-6">
  <select name="year" class="input-field w-32" onchange="this.form.submit()">
    @for($y = now()->year; $y >= now()->year - 3; $y--)
      <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
    @endfor
  </select>
  <select name="month" class="input-field w-40" onchange="this.form.submit()">
    @foreach($months as $i => $m)
      <option value="{{ $i+1 }}" {{ $month == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
    @endforeach
  </select>
</form>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-gold/15">
        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Revenue</span>
    </div>
    <div class="font-sans text-3xl text-bark font-semibold">₱{{ number_format($totalRevenue, 2) }}</div>
    <div class="text-bark-mid/60 text-xs mt-1">{{ $months[$month-1] }} {{ $year }}</div>
  </div>
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-gold/15">
        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Orders</span>
    </div>
    <div class="font-sans text-3xl text-bark font-semibold">{{ $totalOrders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1">{{ $months[$month-1] }} {{ $year }}</div>
  </div>
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-sage/15">
        <svg class="w-5 h-5 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">New Customers</span>
    </div>
    <div class="font-sans text-3xl text-bark font-semibold">{{ $totalCustomers }}</div>
    <div class="text-bark-mid/60 text-xs mt-1">{{ $months[$month-1] }} {{ $year }}</div>
  </div>
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="stat-icon bg-rust/10">
        <svg class="w-5 h-5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </div>
      <span class="text-[10px] text-bark-mid/40 tracking-widest uppercase">Cancelled</span>
    </div>
    <div class="font-sans text-3xl text-bark font-semibold">{{ $cancelledOrders }}</div>
    <div class="text-bark-mid/60 text-xs mt-1">{{ $months[$month-1] }} {{ $year }}</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- Revenue Chart -->
  <div class="lg:col-span-2 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-6">Monthly Revenue — {{ $year }}</h2>
    @php $maxRevenue = max(array_values($revenueByMonth) ?: [1]); @endphp
    <div class="flex items-end gap-2 h-48">
      @foreach($revenueByMonth as $m => $rev)
      <div class="flex-1 flex flex-col items-center gap-1">
        <div class="text-[9px] text-bark-mid/50">{{ $rev > 0 ? '₱'.number_format($rev/1000, 1).'k' : '' }}</div>
        <div class="w-full rounded-t-md transition-all duration-500 {{ $m == $month ? 'bg-gold' : 'bg-gold/30' }}"
          style="height: {{ $maxRevenue > 0 ? round(($rev / $maxRevenue) * 160) : 4 }}px; min-height: 4px;"></div>
        <div class="text-[9px] text-bark-mid/50">{{ $months[$m-1] }}</div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Orders by Status -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Orders by Status</h2>
    @php
      $statusColors = ['pending'=>'bg-gold/20 text-gold','confirmed'=>'bg-blue-500/15 text-blue-600','processing'=>'bg-purple-500/15 text-purple-600','shipped'=>'bg-sky-500/15 text-sky-600','delivered'=>'bg-sage/20 text-sage','cancelled'=>'bg-rust/15 text-rust'];
    @endphp
    @if(empty($ordersByStatus))
      <p class="text-bark-mid/40 text-sm text-center py-8">Walang orders ngayong buwan.</p>
    @else
      <div class="flex flex-col gap-3">
        @foreach($statusColors as $status => $color)
          @if(isset($ordersByStatus[$status]))
          <div class="flex items-center justify-between">
            <span class="text-sm text-bark capitalize">{{ $status }}</span>
            <span class="badge {{ $color }}">{{ $ordersByStatus[$status] }}</span>
          </div>
          @endif
        @endforeach
      </div>
    @endif
  </div>

  <!-- Top Products -->
  <div class="lg:col-span-3 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Top 5 Products — {{ $months[$month-1] }} {{ $year }}</h2>
    @if($topProducts->isEmpty())
      <p class="text-bark-mid/40 text-sm text-center py-8">Walang data ngayong buwan.</p>
    @else
      <table class="w-full">
        <thead>
          <tr class="border-b border-gold/15">
            <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">#</th>
            <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Product</th>
            <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Qty Sold</th>
            <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Revenue</th>
          </tr>
        </thead>
        <tbody>
          @foreach($topProducts as $i => $item)
          <tr class="table-row">
            <td class="py-3 px-4 text-sm text-bark-mid/50">{{ $i + 1 }}</td>
            <td class="py-3 px-4 text-sm text-bark font-medium">{{ $item->product->name ?? '—' }}</td>
            <td class="py-3 px-4 text-sm text-bark">{{ $item->total_qty }} {{ $item->product->unit ?? '' }}</td>
            <td class="py-3 px-4 text-sm text-bark font-semibold">₱{{ number_format($item->total_revenue, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

</div>
@endsection
