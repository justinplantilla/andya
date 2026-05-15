@extends('customer.layout')
@section('title', 'My Orders')
@section('page-title', 'My Orders')
@section('page-subtitle', 'Lahat ng iyong mga order')

@section('content')
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
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Cash on Delivery</span>
          </div>

        </div>

        <div class="flex items-center gap-4">
          <div class="text-right">
            <div class="text-xs text-bark-mid/50">Order Total</div>
            <div class="font-sans text-base text-bark font-semibold">₱{{ number_format($order->total_amount, 2) }}</div>
          </div>

          <div class="flex items-center gap-2">
            <button type="button"
              onclick="viewDetails({{ $order->id }}, '{{ $order->order_number }}', '{{ $order->status }}', '{{ $order->created_at->format('M d, Y h:i A') }}', {{ $order->total_amount }}, '{{ addslashes($order->shipping_address) }}', '{{ $order->invoice->invoice_number ?? '' }}', '{{ $order->invoice->status ?? 'unpaid' }}', {{ $order->orderItems->map(fn($i) => ['name' => $i->product->name, 'category' => $i->product->category->name, 'qty' => $i->quantity, 'unit' => $i->product->unit, 'unit_price' => $i->unit_price, 'subtotal' => $i->total_price, 'image' => $i->product->image ? asset('storage/'.$i->product->image) : ''])->toJson() }})"
              class="text-xs text-gold hover:text-rust transition-colors tracking-wide flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              View Details
            </button>
            @if($order->status === 'pending')
              <button type="button"
                onclick="confirmCancel({{ $order->id }}, '{{ $order->order_number }}', {{ $order->total_amount }}, {{ $order->orderItems->map(fn($i) => ['name' => $i->product->name, 'qty' => $i->quantity, 'unit' => $i->product->unit, 'subtotal' => $i->total_price])->toJson() }})"
                class="text-xs text-rust/60 hover:text-rust transition-colors tracking-wide flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
              </button>
            @endif
            @if(in_array($order->status, ['delivered','cancelled']))
              <form method="POST" action="{{ route('customer.orders.reorder', $order->id) }}">
                @csrf
                <button type="submit" class="text-xs text-sage hover:text-bark transition-colors tracking-wide flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  Reorder
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>

    </div>
    @endforeach
  </div>

  <div class="mt-4">{{ $orders->appends(request()->query())->links() }}</div>
@endif

<!-- Order Details Modal -->
<div id="details-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeDetailsModal()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-2xl z-10 max-h-[90vh] flex flex-col">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gold/15 flex-shrink-0">
      <div>
        <h2 class="font-display text-xl text-bark font-medium">Order Details</h2>
        <p class="text-xs text-bark-mid/50" id="details-order-number"></p>
      </div>
      <button onclick="closeDetailsModal()" class="text-bark/30 hover:text-bark transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- Body (scrollable) -->
    <div class="px-6 py-4 overflow-y-auto flex-1">
      <!-- Status & Info -->
      <div class="flex items-center justify-between mb-4">
        <span class="badge text-sm px-4 py-1.5" id="details-status-badge"></span>
        <span class="text-xs text-bark-mid/50" id="details-date"></span>
      </div>

      <div class="grid grid-cols-2 gap-3 mb-5 text-sm">
        <div>
          <div class="text-xs text-bark-mid/50 mb-0.5">Invoice #</div>
          <div class="text-bark font-medium" id="details-invoice"></div>
        </div>
        <div>
          <div class="text-xs text-bark-mid/50 mb-0.5">Payment</div>
          <div id="details-payment-status"></div>
        </div>
        <div>
          <div class="text-xs text-bark-mid/50 mb-0.5">Payment Method</div>
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-bark-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="text-sm text-bark">Cash on Delivery</span>
          </div>
        </div>
        <div>
          <div class="text-xs text-bark-mid/50 mb-0.5">Total Amount</div>
          <div class="text-bark font-semibold" id="details-total"></div>
        </div>
      </div>

      <!-- Items -->
      <div class="mb-5">
        <h3 class="text-sm font-semibold text-bark mb-3">Items Ordered</h3>
        <div id="details-items-list" class="flex flex-col gap-3"></div>
      </div>

      <!-- Shipping Address -->
      <div>
        <h3 class="text-sm font-semibold text-bark mb-2">Shipping Address</h3>
        <div class="flex items-start gap-2 bg-white/50 rounded-lg p-3 border border-gold/15">
          <svg class="w-4 h-4 text-gold mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <p class="text-sm text-bark leading-relaxed" id="details-address"></p>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-gold/10 flex justify-end flex-shrink-0">
      <button onclick="closeDetailsModal()" class="btn-outline">Close</button>
    </div>
  </div>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancel-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeCancelModal()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-md z-10 p-6">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-full bg-rust/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      </div>
      <div>
        <h2 class="font-display text-xl text-bark font-medium">I-cancel ang Order?</h2>
        <p class="text-xs text-bark-mid/50" id="cancel-order-number"></p>
      </div>
    </div>

    <!-- Items list -->
    <div id="cancel-items-list" class="flex flex-col gap-1 mb-3 max-h-40 overflow-y-auto"></div>

    <div class="flex justify-between text-sm border-t border-gold/15 pt-3 mb-4">
      <span class="text-bark-mid/60">Order Total</span>
      <span class="font-semibold text-bark" id="cancel-order-total"></span>
    </div>

    <p class="text-bark-mid/50 text-xs mb-4">Hindi na ito mababago pagkatapos ma-cancel.</p>

    <form method="POST" id="cancel-form" action="">
      @csrf
      @method('PUT')
      <div class="mb-4">
        <label class="text-xs tracking-[0.15em] uppercase text-bark-mid/70 font-medium block mb-2">Dahilan ng Cancellation</label>
        <select name="cancel_reason" required class="w-full bg-white border border-bark/15 rounded-md px-3 py-2.5 text-sm text-bark focus:outline-none focus:border-gold/60">
          <option value="">Pumili ng dahilan...</option>
          <option value="Changed my mind">Nagbago ang isip ko</option>
          <option value="Wrong item ordered">Mali ang na-order na item</option>
          <option value="Found a better price">Mas mura sa ibang lugar</option>
          <option value="Ordered by mistake">Na-order nang hindi sinasadya</option>
          <option value="Delivery takes too long">Masyadong matagal ang delivery</option>
          <option value="Other">Iba pa</option>
        </select>
      </div>
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
  const statusBadgeClass = {
    pending: 'badge-pending', confirmed: 'badge-confirmed', processing: 'badge-processing',
    shipped: 'badge-shipped', delivered: 'badge-delivered', cancelled: 'badge-cancelled'
  };

  function viewDetails(id, orderNumber, status, date, total, address, invoiceNum, invoiceStatus, items) {
    document.getElementById('details-order-number').textContent = orderNumber;
    document.getElementById('details-date').textContent         = date;
    document.getElementById('details-invoice').textContent      = invoiceNum || '—';
    document.getElementById('details-total').textContent        = '₱' + parseFloat(total).toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('details-address').textContent      = address;

    const badge = document.getElementById('details-status-badge');
    badge.className = 'badge ' + (statusBadgeClass[status] || '') + ' text-sm px-4 py-1.5';
    badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

    const payEl = document.getElementById('details-payment-status');
    const isPaid = invoiceStatus === 'paid';
    payEl.innerHTML = '<span class="badge ' + (isPaid ? 'badge-delivered' : 'badge-pending') + '">' + invoiceStatus.charAt(0).toUpperCase() + invoiceStatus.slice(1) + '</span>';

    const list = document.getElementById('details-items-list');
    list.innerHTML = '';
    items.forEach(function(item) {
      const div = document.createElement('div');
      div.className = 'flex items-center gap-3 pb-3 border-b border-gold/10 last:border-0 last:pb-0';
      const imgHtml = item.image
        ? '<img src="' + item.image + '" class="w-full h-full object-cover"/>'
        : '<svg class="w-5 h-5 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>';
      div.innerHTML =
        '<div class="w-12 h-12 rounded-lg bg-bark/5 flex-shrink-0 overflow-hidden flex items-center justify-center">' + imgHtml + '</div>' +
        '<div class="flex-1 min-w-0">' +
          '<div class="text-sm text-bark font-medium">' + item.name + '</div>' +
          '<div class="text-xs text-bark-mid/50">' + item.category + '</div>' +
          '<div class="text-xs text-bark-mid/60 mt-0.5">' + item.qty + ' ' + item.unit + ' × ₱' + parseFloat(item.unit_price).toLocaleString('en-PH',{minimumFractionDigits:2}) + '</div>' +
        '</div>' +
        '<div class="text-sm text-bark font-semibold flex-shrink-0">₱' + parseFloat(item.subtotal).toLocaleString('en-PH',{minimumFractionDigits:2}) + '</div>';
      list.appendChild(div);
    });

    const m = document.getElementById('details-modal');
    m.classList.remove('hidden');
    m.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function closeDetailsModal() {
    const m = document.getElementById('details-modal');
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.body.style.overflow = '';
  }

  function confirmCancel(id, orderNumber, total, items) {
    document.getElementById('cancel-order-number').textContent = orderNumber;
    document.getElementById('cancel-order-total').textContent  = '₱' + parseFloat(total).toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('cancel-form').action = '/customer/orders/' + id + '/cancel';

    const list = document.getElementById('cancel-items-list');
    list.innerHTML = '';
    items.forEach(function(item) {
      const div = document.createElement('div');
      div.className = 'flex items-center justify-between gap-2 py-1.5 border-b border-gold/10 last:border-0';
      div.innerHTML =
        '<span class="text-sm text-bark font-medium truncate flex-1">' + item.name + '</span>' +
        '<span class="text-xs text-bark-mid/60 flex-shrink-0">x' + item.qty + ' ' + item.unit + '</span>' +
        '<span class="text-sm text-bark font-semibold flex-shrink-0">₱' + parseFloat(item.subtotal).toLocaleString('en-PH',{minimumFractionDigits:2}) + '</span>';
      list.appendChild(div);
    });

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

  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeDetailsModal(); closeCancelModal(); } });
</script>
@endsection
