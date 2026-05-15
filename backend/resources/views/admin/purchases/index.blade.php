@extends('admin.layout')
@section('title', 'Purchases')
@section('page-title', 'Purchases')
@section('page-subtitle', 'Mga biniling produkto mula sa suppliers')

@section('content')

<form method="GET" action="{{ route('admin.purchases.index') }}" class="flex items-center gap-3 mb-6">
  <input type="text" name="search" value="{{ request('search') }}" placeholder="Maghanap ng purchase #..." class="input-field w-64"/>
  @if(request('search'))
    <a href="{{ route('admin.purchases.index') }}" class="btn-outline">Clear</a>
  @endif
  <button type="button" onclick="openPurchaseModal()" class="btn-gold ml-auto">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    New Purchase
  </button>
</form>

@if($purchases->isEmpty())
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <div class="flex flex-col items-center justify-center py-16 text-center">
      <div class="w-14 h-14 rounded-full bg-gold/10 flex items-center justify-center mb-4">
        <svg class="w-6 h-6 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <p class="text-bark-mid/50 text-sm">Walang nahanap na purchase.</p>
    </div>
  </div>
@else
  <div class="flex flex-col gap-4">
    @foreach($purchases as $purchase)
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 overflow-hidden">
      <div class="flex items-center justify-between px-5 py-3 border-b border-gold/10">
        <div class="flex items-center gap-4">
          <span class="text-sm text-bark-mid/60 tracking-widest uppercase font-medium">{{ $purchase->purchase_number }}</span>
          <span class="text-sm text-bark-mid/40">{{ $purchase->created_at->format('M d, Y') }}</span>
        </div>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-1.5 text-sm text-bark-mid/60">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            {{ $purchase->supplier->name }}
          </div>
          <span class="badge badge-{{ $purchase->status === 'received' ? 'delivered' : ($purchase->status === 'cancelled' ? 'cancelled' : 'pending') }}">
            {{ ucfirst($purchase->status) }}
          </span>
        </div>
      </div>

      <div class="px-5 py-3 flex flex-col divide-y divide-gold/10">
        @foreach($purchase->purchaseItems as $item)
        <div class="flex items-center gap-4 py-3">
          <div class="w-12 h-12 rounded-xl bg-bark/5 flex-shrink-0 overflow-hidden flex items-center justify-center">
            @if($item->product->image)
              <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover"/>
            @else
              <svg class="w-5 h-5 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-base text-bark font-medium">{{ $item->product->name }}</div>
            <div class="text-sm text-bark-mid/60 mt-1">
              x{{ $item->quantity }} {{ $item->product->unit }}
              <span class="mx-1 text-bark-mid/30">·</span>
              ₱{{ number_format($item->unit_cost, 2) }} / {{ $item->product->unit }}
            </div>
          </div>
          <div class="text-base text-bark font-semibold flex-shrink-0">₱{{ number_format($item->total_cost, 2) }}</div>
        </div>
        @endforeach
      </div>

      <div class="flex items-center justify-between px-5 py-3 border-t border-gold/10">
        @php
          $pStatus = $purchase->status;
          $pColors = [
            'pending'   => 'bg-gold/15 text-gold border-gold/30',
            'received'  => 'bg-sage/15 text-sage border-sage/30',
            'cancelled' => 'bg-rust/10 text-rust border-rust/20',
          ];
        @endphp
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium {{ $pColors[$pStatus] ?? 'bg-bark/5 text-bark border-bark/10' }}">
            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
            {{ ucfirst($pStatus) }}
          </span>
          @if($pStatus === 'pending')
            <form method="POST" action="{{ route('admin.purchases.status', $purchase) }}">
              @csrf @method('PUT')
              <input type="hidden" name="status" value="received"/>
              <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gold/30 bg-gold/8 text-bark-mid text-xs font-medium hover:bg-gold/20 transition-all">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Mark Received
              </button>
            </form>
            <form method="POST" action="{{ route('admin.purchases.status', $purchase) }}">
              @csrf @method('PUT')
              <input type="hidden" name="status" value="cancelled"/>
              <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-rust/20 bg-rust/5 text-rust text-xs font-medium hover:bg-rust/15 transition-all">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
              </button>
            </form>
          @endif
        </div>
        <div class="text-right">
          <div class="text-xs text-bark-mid/50">Total Cost</div>
          <div class="font-sans text-base text-bark font-semibold">₱{{ number_format($purchase->total_cost, 2) }}</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="mt-4">{{ $purchases->appends(request()->query())->links() }}</div>
@endif

<!-- New Purchase Modal -->
<div id="purchase-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closePurchaseModal()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-2xl z-10 flex flex-col max-h-[90vh]">

    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gold/15 flex-shrink-0">
      <div>
        <h2 class="font-display text-xl text-bark font-medium">New Purchase Order</h2>
        <p class="text-xs text-bark-mid/50 tracking-wide mt-0.5">Mag-order ng produkto mula sa supplier</p>
      </div>
      <button onclick="closePurchaseModal()" class="w-8 h-8 rounded-full bg-bark/5 hover:bg-bark/10 flex items-center justify-center transition-colors">
        <svg class="w-4 h-4 text-bark-mid/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- Modal Body -->
    <form method="POST" action="{{ route('admin.purchases.store') }}" id="purchase-form">
      @csrf
      <div class="overflow-y-auto flex-1 px-6 py-5 flex flex-col gap-5">

        <!-- Supplier + Notes -->
        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-2">
            <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Supplier</label>
            <div class="relative" id="supplier-wrapper">
              <input type="text" id="supplier-input" placeholder="Mag-type ng pangalan ng supplier..." class="input-field" autocomplete="off" required/>
              <input type="hidden" name="supplier_id" id="supplier-id"/>
              <div id="supplier-dropdown" class="hidden absolute top-full left-0 right-0 mt-1 bg-cream border border-gold/30 rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto"></div>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Notes <span class="normal-case text-bark-mid/40">(optional)</span></label>
            <textarea name="notes" rows="2" class="input-field resize-none" placeholder="Mga tala..."></textarea>
          </div>
        </div>

        <!-- Items -->
        <div>
          <div class="flex items-center justify-between mb-3">
            <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Mga Produkto</label>
            <button type="button" onclick="addRow()" class="text-xs text-gold hover:text-rust transition-colors flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              Add Item
            </button>
          </div>
          <div id="items-container" class="flex flex-col gap-2"></div>
        </div>

        <!-- Total -->
        <div class="flex justify-end border-t border-gold/15 pt-4">
          <div class="text-right">
            <div class="text-xs text-bark-mid/50 mb-1">Estimated Total</div>
            <div class="font-sans text-2xl text-bark font-semibold" id="grand-total">₱0.00</div>
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gold/15 flex-shrink-0">
        <button type="button" onclick="closePurchaseModal()" class="btn-outline">Cancel</button>
        <button type="submit" class="btn-gold">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Create Purchase Order
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'unit' => $p->unit]));
  let rowIndex = 0;

  function openPurchaseModal() {
    document.getElementById('purchase-modal').classList.remove('hidden');
    document.getElementById('purchase-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    if (document.querySelectorAll('#items-container > div').length === 0) addRow();
  }

  function closePurchaseModal() {
    document.getElementById('purchase-modal').classList.add('hidden');
    document.getElementById('purchase-modal').classList.remove('flex');
    document.body.style.overflow = '';
  }

  function addRow() {
    const container = document.getElementById('items-container');
    const idx = rowIndex++;
    const options = products.map(p => `<option value="${p.id}">${p.name} (${p.unit})</option>`).join('');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 p-3 bg-bark/3 rounded-xl';
    row.id = 'row-' + idx;
    row.innerHTML = `
      <select name="products[${idx}][product_id]" class="input-field flex-1 text-sm py-2" required onchange="updateUnit(${idx}, this)">
        <option value="">— Produkto —</option>
        ${options}
      </select>
      <input type="number" name="products[${idx}][quantity]" placeholder="Qty" min="1" class="input-field w-20 text-sm py-2" required oninput="recalcTotal()"/>
      <input type="number" name="products[${idx}][unit_cost]" placeholder="Unit Cost" min="0" step="0.01" class="input-field w-28 text-sm py-2" required oninput="recalcTotal()"/>
      <span class="text-xs text-bark-mid/50 w-8 flex-shrink-0" id="unit-${idx}"></span>
      <button type="button" onclick="removeRow(${idx})" class="w-7 h-7 rounded-full bg-rust/10 hover:bg-rust/20 flex items-center justify-center flex-shrink-0 transition-colors">
        <svg class="w-3 h-3 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    `;
    container.appendChild(row);
  }

  function removeRow(idx) {
    document.getElementById('row-' + idx)?.remove();
    recalcTotal();
  }

  function updateUnit(idx, select) {
    const product = products.find(p => p.id == select.value);
    document.getElementById('unit-' + idx).textContent = product ? product.unit : '';
  }

  function recalcTotal() {
    let total = 0;
    document.querySelectorAll('#items-container > div').forEach(row => {
      const qty  = parseFloat(row.querySelector('input[name*="quantity"]')?.value) || 0;
      const cost = parseFloat(row.querySelector('input[name*="unit_cost"]')?.value) || 0;
      total += qty * cost;
    });
    document.getElementById('grand-total').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closePurchaseModal(); });

  // Supplier autocomplete
  const suppliers = @json($suppliers->map(fn($s) => ['id' => $s->id, 'name' => $s->name]));
  const supplierInput = document.getElementById('supplier-input');
  const supplierIdInput = document.getElementById('supplier-id');
  const supplierDropdown = document.getElementById('supplier-dropdown');

  supplierInput.addEventListener('input', function () {
    const val = this.value.trim().toLowerCase();
    supplierIdInput.value = '';
    if (!val) { supplierDropdown.classList.add('hidden'); return; }
    const filtered = suppliers.filter(s => s.name.toLowerCase().includes(val));
    if (!filtered.length) {
      supplierDropdown.innerHTML = '<div class="px-4 py-3 text-sm text-bark-mid/40">Walang nahanap</div>';
    } else {
      supplierDropdown.innerHTML = filtered.map(s =>
        `<div class="px-4 py-3 text-sm text-bark cursor-pointer hover:bg-gold/10 transition-colors border-b border-gold/8 last:border-0" data-id="${s.id}" data-name="${s.name}">${s.name}</div>`
      ).join('');
      supplierDropdown.querySelectorAll('[data-id]').forEach(el => {
        el.addEventListener('mousedown', function (e) {
          e.preventDefault();
          supplierInput.value = this.dataset.name;
          supplierIdInput.value = this.dataset.id;
          supplierDropdown.classList.add('hidden');
        });
      });
    }
    supplierDropdown.classList.remove('hidden');
  });

  supplierInput.addEventListener('blur', () => setTimeout(() => supplierDropdown.classList.add('hidden'), 150));
  supplierInput.addEventListener('focus', () => { if (supplierInput.value.trim()) supplierInput.dispatchEvent(new Event('input')); });

  document.getElementById('purchase-form').addEventListener('submit', function (e) {
    if (!supplierIdInput.value) {
      e.preventDefault();
      supplierInput.focus();
      supplierInput.style.borderColor = 'rgba(139,58,26,0.6)';
      setTimeout(() => supplierInput.style.borderColor = '', 2000);
    }
  });
</script>
@endsection
