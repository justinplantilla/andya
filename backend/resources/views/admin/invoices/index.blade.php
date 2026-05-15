@extends('admin.layout')
@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Mga invoice ng mga transaksyon')

@section('content')
<div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
  <form method="GET" action="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Maghanap ng invoice #..." class="input-field w-64"/>
    <select name="status" class="input-field w-44" onchange="this.form.submit()">
      <option value="">All Status</option>
      <option value="unpaid"    {{ request('status') === 'unpaid'    ? 'selected' : '' }}>Unpaid</option>
      <option value="paid"      {{ request('status') === 'paid'      ? 'selected' : '' }}>Paid</option>
      <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    @if(request('search') || request('status'))
      <a href="{{ route('admin.invoices.index') }}" class="btn-outline">Clear</a>
    @endif
  </form>

  <table class="w-full">
    <thead>
      <tr class="border-b border-gold/15">
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Invoice #</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Customer</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Order #</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Amount</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Payment</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Status</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Date</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($invoices as $invoice)
      <tr class="table-row">
        <td class="py-3 px-4 text-sm text-bark font-medium">{{ $invoice->invoice_number }}</td>
        <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $invoice->user->name }}</td>
        <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $invoice->order->order_number }}</td>
        <td class="py-3 px-4 text-sm text-bark">₱{{ number_format($invoice->amount, 2) }}</td>
        <td class="py-3 px-4">
          @if($invoice->order->payment_method === 'gcash')
            <div class="flex items-center gap-1.5">
              <span class="w-5 h-5 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 font-bold text-[9px]">G</span>
              <span class="text-xs text-bark">GCash</span>
            </div>
            @if($invoice->order->gcash_number)
              <div class="text-[10px] text-bark-mid/40 mt-0.5">{{ $invoice->order->gcash_number }}</div>
            @endif
          @else
            <div class="flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-bark-mid/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <span class="text-xs text-bark">COD</span>
            </div>
          @endif
        </td>
        <td class="py-3 px-4">
          <span class="badge badge-{{ $invoice->status === 'paid' ? 'delivered' : ($invoice->status === 'cancelled' ? 'cancelled' : 'pending') }}">
            {{ ucfirst($invoice->status) }}
          </span>
        </td>
        <td class="py-3 px-4 text-xs text-bark-mid/50">{{ $invoice->created_at->format('M d, Y') }}</td>
        <td class="py-3 px-4">
          <div class="flex flex-col gap-1.5">
            @if($invoice->status === 'unpaid' && $invoice->order->payment_method === 'gcash')
              <form method="POST" action="{{ route('admin.invoices.markPaid', $invoice) }}">
                @csrf @method('PUT')
                <button type="submit" class="text-xs text-sage hover:text-sage/70 transition-colors flex items-center gap-1 font-medium">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  Mark as Paid
                </button>
              </form>
            @endif
            <a href="{{ route('admin.invoices.receipt', $invoice) }}" target="_blank"
              class="text-xs text-gold hover:text-rust transition-colors flex items-center gap-1 font-medium">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
              Print Receipt
            </a>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" class="py-16 text-center text-bark-mid/40 text-sm">Walang nahanap na invoice.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-4">{{ $invoices->appends(request()->query())->links() }}</div>
</div>
@endsection
