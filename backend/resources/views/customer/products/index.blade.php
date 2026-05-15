@extends('customer.layout')
@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'I-browse ang mga available na produkto')

@section('content')
@if($products->isEmpty())
  <div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="w-16 h-16 rounded-full bg-gold/10 flex items-center justify-center mb-4">
      <svg class="w-7 h-7 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <p class="text-bark-mid/50 text-sm">Walang nahanap na produkto.</p>
    @if(request('search') || request('category_id'))
      <a href="{{ route('customer.products') }}" class="mt-2 text-xs text-gold hover:text-rust">Tingnan lahat →</a>
    @endif
  </div>
@else
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($products as $product)
    <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col">
      <div class="h-44 bg-white flex items-center justify-center p-3 flex-shrink-0">
        @if($product->image)
          <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-contain"/>
        @else
          <svg class="w-12 h-12 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        @endif
      </div>
      <div class="p-4 flex flex-col flex-1">
        <div class="text-[10px] text-gold tracking-widest uppercase mb-1">{{ $product->category->name }}</div>
        <div class="font-display text-lg text-bark font-medium leading-tight mb-1">{{ $product->name }}</div>
        <div class="text-xs text-bark-mid/50 mb-2">
          {{ $product->inventory ? $product->inventory->quantity : 0 }} {{ $product->unit }} available
        </div>
        <div class="font-sans text-lg text-bark font-semibold mb-3">₱{{ number_format($product->price, 2) }}</div>
        <div class="mt-auto">
        @if($product->inventory && $product->inventory->quantity > 0)
          <div class="flex flex-col gap-2">
            <form method="POST" action="{{ route('customer.cart.add') }}">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}"/>
              <input type="hidden" name="quantity" value="1"/>
              <button type="submit" class="w-full btn-outline py-2 text-xs justify-center flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Add to Cart
              </button>
            </form>
            <button type="button"
              onclick="openBuyNow({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->unit }}', '{{ $product->image ? asset('storage/'.$product->image) : '' }}', {{ $product->inventory->quantity }}, '{{ addslashes($product->category->name) }}')"
              class="w-full btn-gold py-2 text-xs justify-center flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              Buy Now
            </button>
          </div>
        @else
          <span class="badge badge-cancelled text-xs">Out of Stock</span>
        </div>
        @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="mt-6">{{ $products->appends(request()->query())->links() }}</div>
@endif

<!-- ───── BUY NOW MODAL ───── -->
<div id="buy-now-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeBuyNow()"></div>

  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-lg z-10 flex flex-col max-h-[90vh]">

    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30 pointer-events-none"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30 pointer-events-none"></div>

    <!-- Header (fixed) -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gold/15 flex-shrink-0">
      <h2 class="font-display text-xl text-bark font-medium">Buy Now</h2>
      <button onclick="closeBuyNow()" class="text-bark/30 hover:text-bark transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <form method="POST" action="{{ route('customer.orders.direct') }}" id="buy-now-form" class="flex flex-col flex-1 min-h-0" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="product_id" id="modal-product-id"/>
      <input type="hidden" name="quantity" id="modal-quantity-input" value="1"/>

      <!-- Scrollable body -->
      <div class="px-6 py-4 flex flex-col gap-4 overflow-y-auto flex-1">

        <!-- Product Info -->
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-xl bg-white flex-shrink-0 overflow-hidden flex items-center justify-center p-1">
            <img id="modal-image" src="" class="w-full h-full object-contain hidden"/>
            <svg id="modal-image-placeholder" class="w-7 h-7 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-[10px] text-gold tracking-widest uppercase mb-0.5" id="modal-category"></div>
            <div class="font-display text-lg text-bark font-medium leading-tight" id="modal-name"></div>
            <div class="font-sans text-base text-bark font-semibold mt-0.5" id="modal-price"></div>
          </div>
        </div>

        <!-- Quantity -->
        <div class="flex flex-col gap-1.5">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Quantity</label>
          <div class="flex items-center gap-3">
            <button type="button" onclick="changeQty(-1)" class="w-8 h-8 rounded-full border border-bark/15 flex items-center justify-center text-bark-mid/60 hover:border-gold hover:text-gold transition-all font-medium">−</button>
            <span id="modal-qty-display" class="w-8 text-center text-sm text-bark font-semibold">1</span>
            <button type="button" onclick="changeQty(1)" class="w-8 h-8 rounded-full border border-bark/15 flex items-center justify-center text-bark-mid/60 hover:border-gold hover:text-gold transition-all font-medium">+</button>
            <span class="text-xs text-bark-mid/40">max: <span id="modal-max-qty"></span></span>
          </div>
        </div>

        <!-- Total -->
        <div class="flex items-center justify-between py-2.5 border-t border-b border-gold/10">
          <span class="text-sm text-bark-mid/60">Total</span>
          <span class="font-sans text-lg text-bark font-semibold" id="modal-total"></span>
        </div>

        <!-- Payment Method -->
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Payment Method</label>
          <div class="bg-bark/5 border-2 border-gold rounded-xl p-2.5 flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-bark/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-bark-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
              <div class="text-xs font-semibold text-bark">Cash on Delivery</div>
              <div class="text-[10px] text-bark-mid/50">Bayad sa pagdating</div>
            </div>
          </div>
          <input type="hidden" name="payment_method" value="cod"/>
        </div>

        <!-- Shipping Address -->
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Shipping Address</label>
            @if(Auth::user()->address)
              <button type="button" onclick="toggleAddressEdit()" class="text-xs text-gold hover:text-rust transition-colors" id="addr-toggle-btn">Change</button>
            @endif
          </div>
          @if(Auth::user()->address)
            <div id="addr-display" class="bg-white/60 border border-gold/20 rounded-lg p-3 flex items-start gap-2">
              <svg class="w-4 h-4 text-gold mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <p class="text-sm text-bark leading-relaxed" id="addr-text">{{ Auth::user()->address }}</p>
            </div>
            <input type="hidden" name="shipping_address" id="addr-hidden" value="{{ Auth::user()->address }}"/>
            <div id="addr-edit" class="hidden flex-col gap-2">
              <textarea id="addr-textarea" rows="2" class="input-field resize-none" placeholder="Ilagay ang bagong address...">{{ Auth::user()->address }}</textarea>
              <div class="flex gap-2">
                <button type="button" onclick="saveAddress()" class="btn-gold py-1.5 px-4 text-xs">Gamitin Ito</button>
                <button type="button" onclick="cancelAddress()" class="btn-outline py-1.5 px-4 text-xs">Cancel</button>
              </div>
            </div>
          @else
            <textarea name="shipping_address" rows="2" class="input-field resize-none" placeholder="Ilagay ang iyong address..." required></textarea>
            <p class="text-xs text-bark-mid/40">Mase-save ito para sa susunod.</p>
          @endif
        </div>

      </div>

      <!-- Footer (fixed) -->
      <div class="px-6 py-4 border-t border-gold/10 flex gap-3 flex-shrink-0">
        <button type="button" onclick="closeBuyNow()" class="btn-outline flex-1 justify-center">Cancel</button>
        <button type="button" onclick="showConfirm()" class="btn-gold flex-1 justify-center">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Confirm Order
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ───── ORDER CONFIRMATION MODAL ───── -->
<div id="confirm-modal" class="hidden fixed inset-0 z-[60] items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/50 backdrop-blur-sm" onclick="hideConfirm()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-md z-10 p-6">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>
    <h3 class="font-display text-xl text-bark font-medium mb-4">Kumpirmahin ang Order?</h3>
    <!-- Product detail row -->
    <div class="flex items-center gap-4 p-3 bg-white/50 rounded-xl border border-gold/15 mb-4">
      <div class="w-14 h-14 rounded-lg bg-white flex-shrink-0 overflow-hidden flex items-center justify-center p-1">
        <img id="confirm-image" src="" class="w-full h-full object-contain hidden"/>
        <svg id="confirm-image-placeholder" class="w-6 h-6 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-[10px] text-gold tracking-widest uppercase mb-0.5" id="confirm-category"></div>
        <div class="text-sm text-bark font-semibold leading-tight" id="confirm-name"></div>
        <div class="text-xs text-bark-mid/50 mt-0.5" id="confirm-unit-price"></div>
      </div>
      <div class="text-right flex-shrink-0">
        <div class="text-xs text-bark-mid/50">Qty</div>
        <div class="text-sm text-bark font-semibold" id="confirm-qty"></div>
      </div>
    </div>
    <div class="flex justify-between text-sm border-t border-gold/15 pt-3 mb-5">
      <span class="text-bark-mid/60">Kabuuan</span>
      <span class="font-semibold text-gold" id="confirm-total"></span>
    </div>
    <div class="flex gap-3">
      <button onclick="hideConfirm()" class="flex-1 btn-outline">Bumalik</button>
      <button onclick="document.getElementById('buy-now-form').submit()" class="flex-1 btn-gold justify-center">I-order Na</button>
    </div>
  </div>
</div>

<script>
  let modalPrice = 0, modalMaxQty = 0, modalQty = 1;

  function selectPayment(method, label) {}

  function openBuyNow(id, name, price, unit, image, maxQty, category) {
    modalPrice = price; modalMaxQty = maxQty; modalQty = 1;
    document.getElementById('modal-product-id').value        = id;
    document.getElementById('modal-quantity-input').value    = 1;
    document.getElementById('modal-name').textContent        = name;
    document.getElementById('modal-category').textContent    = category;
    document.getElementById('modal-price').textContent       = '₱' + price.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('modal-max-qty').textContent     = maxQty + ' ' + unit;
    document.getElementById('modal-qty-display').textContent = 1;
    updateModalTotal();

    const img = document.getElementById('modal-image');
    const ph  = document.getElementById('modal-image-placeholder');
    if (image) { img.src = image; img.classList.remove('hidden'); ph.classList.add('hidden'); }
    else        { img.classList.add('hidden'); ph.classList.remove('hidden'); }

    const modal = document.getElementById('buy-now-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function closeBuyNow() {
    document.getElementById('buy-now-modal').classList.add('hidden');
    document.getElementById('buy-now-modal').classList.remove('flex');
    document.body.style.overflow = '';
  }

  function changeQty(delta) {
    modalQty = Math.min(modalMaxQty, Math.max(1, modalQty + delta));
    document.getElementById('modal-qty-display').textContent = modalQty;
    document.getElementById('modal-quantity-input').value    = modalQty;
    updateModalTotal();
  }

  function updateModalTotal() {
    document.getElementById('modal-total').textContent = '₱' + (modalPrice * modalQty).toLocaleString('en-PH', {minimumFractionDigits:2});
  }

  function toggleAddressEdit() {
    document.getElementById('addr-display').classList.add('hidden');
    document.getElementById('addr-edit').classList.remove('hidden');
    document.getElementById('addr-edit').classList.add('flex');
    document.getElementById('addr-toggle-btn').textContent = '';
    document.getElementById('addr-textarea').focus();
  }

  function saveAddress() {
    const val = document.getElementById('addr-textarea').value.trim();
    if (!val) return;
    document.getElementById('addr-text').textContent = val;
    document.getElementById('addr-hidden').value     = val;
    document.getElementById('addr-display').classList.remove('hidden');
    document.getElementById('addr-edit').classList.add('hidden');
    document.getElementById('addr-edit').classList.remove('flex');
    document.getElementById('addr-toggle-btn').textContent = 'Change';
  }

  function cancelAddress() {
    document.getElementById('addr-display').classList.remove('hidden');
    document.getElementById('addr-edit').classList.add('hidden');
    document.getElementById('addr-edit').classList.remove('flex');
    document.getElementById('addr-toggle-btn').textContent = 'Change';
  }

  function showConfirm() {
    document.getElementById('confirm-name').textContent      = document.getElementById('modal-name').textContent;
    document.getElementById('confirm-category').textContent  = document.getElementById('modal-category').textContent;
    document.getElementById('confirm-qty').textContent       = modalQty;
    document.getElementById('confirm-unit-price').textContent= '₱' + modalPrice.toLocaleString('en-PH', {minimumFractionDigits:2}) + ' / unit';
    document.getElementById('confirm-total').textContent     = document.getElementById('modal-total').textContent;

    const img = document.getElementById('modal-image');
    const cImg = document.getElementById('confirm-image');
    const cPh  = document.getElementById('confirm-image-placeholder');
    if (img.src && !img.classList.contains('hidden')) {
      cImg.src = img.src; cImg.classList.remove('hidden'); cPh.classList.add('hidden');
    } else {
      cImg.classList.add('hidden'); cPh.classList.remove('hidden');
    }

    const m = document.getElementById('confirm-modal');
    m.classList.remove('hidden');
    m.classList.add('flex');
  }

  function hideConfirm() {
    const m = document.getElementById('confirm-modal');
    m.classList.add('hidden');
    m.classList.remove('flex');
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeBuyNow(); hideConfirm(); } });
</script>
@endsection
