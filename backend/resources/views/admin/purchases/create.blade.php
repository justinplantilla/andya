@extends('admin.layout')
@section('title', 'Create Purchase')
@section('page-title', 'Create Purchase')
@section('page-subtitle', 'Mag-order ng produkto mula sa supplier')

@section('content')
@if($errors->any())
  <div class="mb-4 text-rust text-sm bg-rust/10 py-3 px-4 rounded-lg">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('admin.purchases.store') }}" id="purchase-form">
  @csrf
  <div class="max-w-3xl flex flex-col gap-6">

    <!-- Supplier + Notes -->
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
      <h2 class="font-display text-xl text-bark font-medium mb-5">Purchase Details</h2>
      <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Supplier</label>
          <select name="supplier_id" class="input-field" required>
            <option value="">— Pumili ng Supplier —</option>
            @foreach($suppliers as $supplier)
              <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Notes <span class="normal-case text-bark-mid/40">(optional)</span></label>
          <textarea name="notes" rows="2" class="input-field resize-none" placeholder="Mga tala...">{{ old('notes') }}</textarea>
        </div>
      </div>
    </div>

    <!-- Items -->
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-display text-xl text-bark font-medium">Mga Produkto</h2>
        <button type="button" onclick="addRow()" class="btn-gold py-2 px-4 text-xs">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Add Item
        </button>
      </div>

      <div id="items-container" class="flex flex-col gap-3">
        <!-- Row template rendered by JS -->
      </div>

      <div class="border-t border-gold/15 mt-5 pt-4 flex justify-end">
        <div class="text-right">
          <div class="text-xs text-bark-mid/50 mb-1">Estimated Total</div>
          <div class="font-sans text-2xl text-bark font-semibold" id="grand-total">₱0.00</div>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('admin.purchases.index') }}" class="btn-outline">Cancel</a>
      <button type="submit" class="btn-gold">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Create Purchase Order
      </button>
    </div>

  </div>
</form>

<script>
  const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'unit' => $p->unit]));
  let rowIndex = 0;

  function addRow() {
    const container = document.getElementById('items-container');
    const idx = rowIndex++;
    const options = products.map(p => `<option value="${p.id}">${p.name} (${p.unit})</option>`).join('');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-3 p-3 bg-bark/3 rounded-xl';
    row.id = 'row-' + idx;
    row.innerHTML = `
      <select name="products[${idx}][product_id]" class="input-field flex-1" required onchange="updateUnit(${idx}, this)">
        <option value="">— Produkto —</option>
        ${options}
      </select>
      <input type="number" name="products[${idx}][quantity]" placeholder="Qty" min="1" class="input-field w-24" required oninput="recalcTotal()"/>
      <input type="number" name="products[${idx}][unit_cost]" placeholder="Unit Cost" min="0" step="0.01" class="input-field w-32" required oninput="recalcTotal()"/>
      <span class="text-sm text-bark-mid/50 w-8" id="unit-${idx}"></span>
      <button type="button" onclick="removeRow(${idx})" class="w-8 h-8 rounded-full bg-rust/10 hover:bg-rust/20 flex items-center justify-center flex-shrink-0 transition-colors">
        <svg class="w-3.5 h-3.5 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
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
      const qty = parseFloat(row.querySelector('input[name*="quantity"]')?.value) || 0;
      const cost = parseFloat(row.querySelector('input[name*="unit_cost"]')?.value) || 0;
      total += qty * cost;
    });
    document.getElementById('grand-total').textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2});
  }

  // Start with one row
  addRow();
</script>
@endsection
