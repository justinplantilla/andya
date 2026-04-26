@extends('admin.layout')
@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-subtitle', 'Lahat ng registered customers')

@section('content')
<form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-3 mb-6">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="Maghanap ng pangalan o email..." class="input-field w-72"/>
  @if(request('search'))
    <a href="{{ route('admin.customers.index') }}" class="btn-outline">Clear</a>
  @endif
</form>

<div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
  @if($customers->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
      <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mb-4">
        <svg class="w-6 h-6 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <p class="text-bark-mid/50 text-sm">Walang nahanap na customer.</p>
    </div>
  @else
    <table class="w-full">
      <thead>
        <tr class="border-b border-gold/15">
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Customer</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Email</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Address</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Orders</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Verified</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Joined</th>
        </tr>
      </thead>
      <tbody>
        @foreach($customers as $customer)
        <tr class="table-row">
          <td class="py-3 px-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold/40 to-bark-mid flex items-center justify-center text-cream font-display text-sm font-medium flex-shrink-0">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
              </div>
              <span class="text-sm text-bark font-medium">{{ $customer->name }}</span>
            </div>
          </td>
          <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $customer->email }}</td>
          <td class="py-3 px-4 text-sm text-bark-mid/60 max-w-[180px] truncate">{{ $customer->address ?? '—' }}</td>
          <td class="py-3 px-4">
            <span class="badge badge-pending">{{ $customer->orders_count }}</span>
          </td>
          <td class="py-3 px-4">
            @if($customer->email_verified_at)
              <span class="badge badge-delivered">Verified</span>
            @else
              <span class="badge badge-cancelled">Unverified</span>
            @endif
          </td>
          <td class="py-3 px-4 text-xs text-bark-mid/50">{{ $customer->created_at->format('M d, Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-4">{{ $customers->appends(request()->query())->links() }}</div>
  @endif
</div>
@endsection
