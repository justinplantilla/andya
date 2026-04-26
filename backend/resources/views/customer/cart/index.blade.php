@extends('customer.layout')
@section('title', 'Cart')
@section('page-title', 'Cart')
@section('page-subtitle', 'Mga item na gusto mong i-order')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif

@if(empty($cart))
  <div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="w-16 h-16 rounded-full bg-gold/10 flex items-center justify-center mb-4">
      <svg class="w-7 h-7 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <p class="text-bark-mid/50 text-sm mb-3">Walang laman ang iyong cart.</p>
    <a href="{{ route('customer.products') }}" class="btn-gold">Mag-browse ng Products</a>
  </div>
@else
<form method="POST" action="{{ route('customer.orders.store') }}" id="checkout-form">
  @csrf
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Cart Items -->
    <div class="lg:col-span-2 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">

      <!-- Select All -->
      <div class="flex items-center gap-3 pb-4 mb-2 border-b border-gold/10">
        <input type="checkbox" id="select-all" class="w-4 h-4 accent-gold cursor-pointer"/>
        <label for="select-all" class="text-sm text-bark-mid/70 cursor-pointer select-none">Piliin Lahat</label>
        <span class="ml-auto text-xs text-bark-mid/40" id="selected-count">0 item(s) napili</span>
      </div>

      <!-- Items -->
      <div class="flex flex-col gap-1" id="cart-items">
        @foreach($cart as $id => $item)
        <div class="cart-item flex items-center gap-4 py-4 border-b border-gold/10 last:border-0" data-price="{{ $item['price'] }}" data-id="{{ $id }}">

          <!-- Checkbox -->
          <input type="checkbox" name="selected_items[]" value="{{ $id }}"
            class="item-checkbox w-4 h-4 accent-gold cursor-pointer flex-shrink-0"
            {{ isset($buyNowId) && $buyNowId == $id ? 'checked' : '' }}/>

          <!-- Product Info -->
          <div class="w-14 h-14 rounded-lg overflow-hidden bg-bark/5 flex-shrink-0 flex items-center justify-center">
            @if($item['image'] ?? null)
              <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover"/>
            @else
              <svg class="w-6 h-6 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
          </div>
          <div class="flex-1 min-w-0">
            <div class="font-medium text-sm text-bark">{{ $item['name'] }}</div>
            <div class="text-xs text-bark-mid/50 mt-0.5">₱{{ number_format($item['price'], 2) }} / {{ $item['unit'] ?? 'pcs' }}</div>
          </div>

          <!-- Quantity Controls -->
          <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('customer.cart.update') }}" class="flex items-center gap-1">
              @csrf
              <input type="hidden" name="product_id" value="{{ $id }}"/>
              <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}"
                class="w-7 h-7 rounded-full border border-bark/15 flex items-center justify-center text-bark-mid/60 hover:border-gold hover:text-gold transition-all text-sm font-medium">−</button>
              <span class="w-8 text-center text-sm text-bark font-medium">{{ $item['quantity'] }}</span>
              <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                class="w-7 h-7 rounded-full border border-bark/15 flex items-center justify-center text-bark-mid/60 hover:border-gold hover:text-gold transition-all text-sm font-medium">+</button>
            </form>
          </div>

          <!-- Subtotal -->
          <div class="w-24 text-right">
            <div class="text-sm font-semibold text-bark item-subtotal">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
          </div>

          <!-- Remove -->
          <form method="POST" action="{{ route('customer.cart.remove') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $id }}"/>
            <button type="submit" class="text-bark/20 hover:text-rust transition-colors ml-1">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </form>

        </div>
        @endforeach
      </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6 h-fit sticky top-20">
      <h2 class="font-display text-xl text-bark font-medium mb-5">Order Summary</h2>

      <div class="flex flex-col gap-3 mb-5">
        <div class="flex justify-between text-sm">
          <span class="text-bark-mid/60">Selected Items</span>
          <span class="text-bark font-medium" id="summary-count">0 item(s)</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-bark-mid/60">Subtotal</span>
          <span class="text-bark font-medium" id="summary-subtotal">₱0.00</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-bark-mid/60">Shipping</span>
          <span class="text-bark font-medium">₱0.00</span>
        </div>
        <div class="border-t border-gold/15 pt-3 flex justify-between">
          <span class="text-bark font-semibold">Total</span>
          <span class="text-bark font-semibold text-lg" id="summary-total">₱0.00</span>
        </div>
      </div>

      <!-- Payment Method -->
      <div class="flex flex-col gap-2 mb-4">
        <label class="text-xs tracking-widests uppercase text-bark-mid/60 font-medium">Payment Method</label>
        <div class="grid grid-cols-2 gap-3">
          <label class="cursor-pointer" onclick="selectCartPayment('cod', this)">
            <input type="radio" name="payment_method" value="cod" class="hidden" checked/>
            <div id="cart-pay-cod" class="border-2 border-gold bg-gold/10 rounded-xl p-3 flex items-center gap-2 transition-all">
              <div class="w-8 h-8 rounded-full bg-bark/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-bark-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <div>
                <div class="text-xs font-semibold text-bark">Cash on Delivery</div>
                <div class="text-[10px] text-bark-mid/50">Bayad sa pagdating</div>
              </div>
            </div>
          </label>
          <label class="cursor-pointer" onclick="selectCartPayment('gcash', this)">
            <input type="radio" name="payment_method" value="gcash" class="hidden"/>
            <div id="cart-pay-gcash" class="border-2 border-transparent bg-bark/5 rounded-xl p-3 flex items-center gap-2 transition-all">
              <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                <span class="text-blue-600 font-bold text-xs">G</span>
              </div>
              <div>
                <div class="text-xs font-semibold text-bark">GCash</div>
                <div class="text-[10px] text-bark-mid/50">Online payment</div>
              </div>
            </div>
          </label>
        </div>
        <div id="cart-gcash-field" class="hidden flex flex-col gap-1.5 mt-1">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">GCash Number</label>
          <input type="text" name="gcash_number" class="input-field" placeholder="09XX XXX XXXX" maxlength="11"/>
          @if($gcash_number)
          <div class="flex items-center gap-2 bg-blue-500/5 border border-blue-500/20 rounded-lg p-3 mt-1">
            <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center flex-shrink-0">
              <span class="text-blue-600 font-bold text-xs">G</span>
            </div>
            <div>
              <div class="text-[10px] text-bark-mid/50 uppercase tracking-widest">Ipadala sa GCash number na ito</div>
              <div class="text-sm text-bark font-semibold">{{ $gcash_number }}</div>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- Shipping Address -->
      <div class="flex flex-col gap-2 mb-4">
        <div class="flex items-center justify-between">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Shipping Address</label>
          @if(Auth::user()->address)
            <button type="button" id="change-address-btn" class="text-xs text-gold hover:text-rust transition-colors">Change</button>
          @endif
        </div>

        @if(Auth::user()->address)
          <!-- Saved address display -->
          <div id="saved-address" class="bg-white/60 border border-gold/20 rounded-lg p-3">
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 text-gold mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <p class="text-sm text-bark leading-relaxed">{{ Auth::user()->address }}</p>
            </div>
            <input type="hidden" name="shipping_address" id="hidden-address" value="{{ Auth::user()->address }}"/>
          </div>

          <!-- Edit address (hidden by default) -->
          <div id="edit-address" class="hidden">
            <textarea name="shipping_address" id="address-textarea" rows="3"
              class="input-field resize-none"
              placeholder="Ilagay ang bagong address...">{{ Auth::user()->address }}</textarea>
            <div class="flex gap-2 mt-2">
              <button type="button" id="save-address-btn" class="btn-gold py-1.5 px-4 text-xs">Gamitin ang Address na Ito</button>
              <button type="button" id="cancel-address-btn" class="btn-outline py-1.5 px-4 text-xs">Cancel</button>
            </div>
          </div>
        @else
          <!-- No saved address yet -->
          <textarea name="shipping_address" rows="3"
            class="input-field resize-none"
            placeholder="Ilagay ang iyong address..." required></textarea>
          <p class="text-xs text-bark-mid/40">Mase-save ito para sa susunod na order mo.</p>
        @endif
      </div>

      <!-- Checkout Button -->
      <button type="submit" id="checkout-btn" disabled
        class="w-full btn-gold justify-center opacity-50 cursor-not-allowed transition-all"
        onclick="return validateCheckout()">
        Mag-Checkout
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </button>

      <p class="text-xs text-bark-mid/40 text-center mt-3" id="checkout-hint">Pumili ng kahit isang item para mag-checkout</p>
    </div>

  </div>
</form>
@endif

<script>
  const checkboxes  = document.querySelectorAll('.item-checkbox');
  const selectAll   = document.getElementById('select-all');
  const countEl     = document.getElementById('selected-count');
  const summaryCount= document.getElementById('summary-count');
  const subtotalEl  = document.getElementById('summary-subtotal');
  const totalEl     = document.getElementById('summary-total');
  const checkoutBtn = document.getElementById('checkout-btn');
  const hintEl      = document.getElementById('checkout-hint');

  function updateSummary() {
    let total = 0, count = 0;
    checkboxes.forEach(cb => {
      if (cb.checked) {
        const row   = cb.closest('.cart-item');
        const price = parseFloat(row.dataset.price);
        const qty   = parseInt(row.querySelector('span.text-center').textContent);
        total += price * qty;
        count++;
      }
    });

    countEl.textContent      = count + ' item(s) napili';
    summaryCount.textContent = count + ' item(s)';
    subtotalEl.textContent   = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    totalEl.textContent      = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });

    if (count > 0) {
      checkoutBtn.disabled = false;
      checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
      hintEl.textContent = count + ' item(s) ang iche-checkout';
    } else {
      checkoutBtn.disabled = true;
      checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
      hintEl.textContent = 'Pumili ng kahit isang item para mag-checkout';
    }

    // Update select all state
    selectAll.checked       = count === checkboxes.length && count > 0;
    selectAll.indeterminate = count > 0 && count < checkboxes.length;
  }

  checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

  selectAll.addEventListener('change', function () {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateSummary();
  });

  function validateCheckout() {
    const selected = document.querySelectorAll('.item-checkbox:checked');
    if (selected.length === 0) {
      alert('Pumili ng kahit isang item para mag-checkout.');
      return false;
    }
    return true;
  }

  function selectCartPayment(method, label) {
    const codCard   = document.getElementById('cart-pay-cod');
    const gcashCard = document.getElementById('cart-pay-gcash');
    const gcashField= document.getElementById('cart-gcash-field');

    codCard.classList.remove('border-gold', 'bg-gold/10');
    codCard.classList.add('border-transparent', 'bg-bark/5');
    gcashCard.classList.remove('border-gold', 'bg-gold/10');
    gcashCard.classList.add('border-transparent', 'bg-bark/5');

    if (method === 'cod') {
      codCard.classList.add('border-gold', 'bg-gold/10');
      codCard.classList.remove('border-transparent', 'bg-bark/5');
      gcashField.classList.add('hidden');
      gcashField.querySelector('input').required = false;
    } else {
      gcashCard.classList.add('border-gold', 'bg-gold/10');
      gcashCard.classList.remove('border-transparent', 'bg-bark/5');
      gcashField.classList.remove('hidden');
      gcashField.querySelector('input').required = true;
    }

    label.querySelector('input[type=radio]').checked = true;
  }

  // Init
  updateSummary();

  // Address change logic
  const changeBtn  = document.getElementById('change-address-btn');
  const savedDiv   = document.getElementById('saved-address');
  const editDiv    = document.getElementById('edit-address');
  const saveBtn    = document.getElementById('save-address-btn');
  const cancelBtn  = document.getElementById('cancel-address-btn');
  const hiddenAddr = document.getElementById('hidden-address');
  const addrTA     = document.getElementById('address-textarea');

  if (changeBtn) {
    changeBtn.addEventListener('click', function () {
      savedDiv.classList.add('hidden');
      editDiv.classList.remove('hidden');
      // Remove hidden input so textarea takes over
      if (hiddenAddr) hiddenAddr.disabled = true;
      addrTA.removeAttribute('disabled');
      addrTA.focus();
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', function () {
      const newAddr = addrTA.value.trim();
      if (!newAddr) { addrTA.focus(); return; }
      // Update display
      savedDiv.querySelector('p').textContent = newAddr;
      if (hiddenAddr) { hiddenAddr.value = newAddr; hiddenAddr.disabled = false; }
      addrTA.setAttribute('disabled', true);
      savedDiv.classList.remove('hidden');
      editDiv.classList.add('hidden');
    });
  }

  if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
      if (hiddenAddr) hiddenAddr.disabled = false;
      addrTA.setAttribute('disabled', true);
      savedDiv.classList.remove('hidden');
      editDiv.classList.add('hidden');
    });
  }
</script>
@endsection
