@extends('admin.layout')
@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('page-subtitle', 'Subaybayan ang stock ng mga produkto')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif

<div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
  <div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex items-center gap-3">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Maghanap ng produkto..." class="input-field w-64"/>
      <select name="status" class="input-field w-44" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="in"  {{ request('status') === 'in'  ? 'selected' : '' }}>In Stock</option>
        <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>Low Stock</option>
        <option value="out" {{ request('status') === 'out' ? 'selected' : '' }}>Out of Stock</option>
      </select>
      @if(request('search') || request('status'))
        <a href="{{ route('admin.inventory.index') }}" class="btn-outline">Clear</a>
      @endif
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-gold">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Add Product
    </a>
  </div>

  <table class="w-full">
    <thead>
      <tr class="border-b border-gold/15">
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Product</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Category</th>
        <th class="text-left py-3 px-4 text-xs tracking-widests uppercase text-bark-mid/50 font-medium">Stock</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Unit</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Alert At</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Status</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($inventory as $item)
      @php
        $status = $item->quantity == 0 ? 'out' : ($item->quantity <= $item->low_stock_threshold ? 'low' : 'in');
      @endphp
      <tr class="table-row">
        <td class="py-3 px-4 text-sm text-bark font-medium">{{ $item->product->name }}</td>
        <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $item->product->category->name }}</td>
        <td class="py-3 px-4 text-sm text-bark font-semibold">{{ $item->quantity }}</td>
        <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $item->product->unit }}</td>
        <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $item->low_stock_threshold }}</td>
        <td class="py-3 px-4">
          @if($status === 'out')
            <span class="badge badge-cancelled">Out of Stock</span>
          @elseif($status === 'low')
            <span class="badge badge-pending">Low Stock</span>
          @else
            <span class="badge badge-active">In Stock</span>
          @endif
        </td>
        <td class="py-3 px-4">
          <button onclick="openAdjust({{ $item->id }}, '{{ addslashes($item->product->name) }}', {{ $item->quantity }}, '{{ $item->product->unit }}')"
            class="text-xs text-gold hover:text-rust transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Adjust
          </button>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="py-16 text-center text-bark-mid/40 text-sm">Walang nahanap.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-4">{{ $inventory->appends(request()->query())->links() }}</div>
</div>

<!-- Adjust Stock Modal -->
<div id="adjust-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeAdjust()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-sm z-10 p-6">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <h2 class="font-display text-xl text-bark font-medium mb-1">Adjust Stock</h2>
    <p class="text-sm text-bark-mid/60 mb-1" id="adjust-product-name"></p>
    <p class="text-xs text-bark-mid/40 mb-5">Current stock: <span class="font-semibold text-bark" id="adjust-current"></span></p>

    <form method="POST" id="adjust-form" action="" class="flex flex-col gap-4">
      @csrf
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Action</label>
        <div class="grid grid-cols-3 gap-2">
          <label class="cursor-pointer" onclick="selectAction('add', this)">
            <input type="radio" name="action" value="add" class="hidden" checked/>
            <div id="action-add" class="border-2 border-gold bg-gold/10 rounded-lg py-2 text-center text-xs font-semibold text-bark transition-all">+ Add</div>
          </label>
          <label class="cursor-pointer" onclick="selectAction('subtract', this)">
            <input type="radio" name="action" value="subtract" class="hidden"/>
            <div id="action-subtract" class="border-2 border-transparent bg-bark/5 rounded-lg py-2 text-center text-xs font-semibold text-bark transition-all">− Subtract</div>
          </label>
          <label class="cursor-pointer" onclick="selectAction('set', this)">
            <input type="radio" name="action" value="set" class="hidden"/>
            <div id="action-set" class="border-2 border-transparent bg-bark/5 rounded-lg py-2 text-center text-xs font-semibold text-bark transition-all">= Set</div>
          </label>
        </div>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Quantity</label>
        <input type="number" name="quantity" min="0" class="input-field" placeholder="0" required/>
      </div>
      <div class="flex gap-3 mt-1">
        <button type="button" onclick="closeAdjust()" class="btn-outline flex-1 justify-center">Cancel</button>
        <button type="submit" class="btn-gold flex-1 justify-center">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openAdjust(id, name, current, unit) {
    document.getElementById('adjust-product-name').textContent = name;
    document.getElementById('adjust-current').textContent = current + ' ' + unit;
    document.getElementById('adjust-form').action = '/admin/inventory/' + id + '/adjust';
    document.getElementById('adjust-modal').classList.remove('hidden');
    document.getElementById('adjust-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    // Reset to add
    selectAction('add');
    document.querySelector('#adjust-form input[name=quantity]').value = '';
  }

  function closeAdjust() {
    document.getElementById('adjust-modal').classList.add('hidden');
    document.getElementById('adjust-modal').classList.remove('flex');
    document.body.style.overflow = '';
  }

  function selectAction(action) {
    ['add','subtract','set'].forEach(a => {
      const el = document.getElementById('action-' + a);
      if (a === action) {
        el.classList.add('border-gold', 'bg-gold/10');
        el.classList.remove('border-transparent', 'bg-bark/5');
      } else {
        el.classList.remove('border-gold', 'bg-gold/10');
        el.classList.add('border-transparent', 'bg-bark/5');
      }
    });
    document.querySelector(`#adjust-form input[value="${action}"]`).checked = true;
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAdjust(); });
</script>
@endsection
