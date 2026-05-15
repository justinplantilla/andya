@extends('admin.layout')
@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Pamahalaan ang mga produkto')

@section('content')

<div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
  <div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center gap-3">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Maghanap ng produkto..." class="input-field w-64"/>
      <select name="category_id" class="input-field w-44" onchange="this.form.submit()">
        <option value="">Lahat ng Category</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      @if(request('search') || request('category_id'))
        <a href="{{ route('admin.products.index') }}" class="btn-outline">Clear</a>
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
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Price</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Stock</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Status</th>
        <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $product)
      <tr class="table-row">
        <td class="py-3 px-4 text-sm text-bark font-medium">{{ $product->name }}</td>
        <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $product->category->name }}</td>
        <td class="py-3 px-4 text-sm text-bark">₱{{ number_format($product->price, 2) }}</td>
        <td class="py-3 px-4 text-sm text-bark">{{ $product->inventory->quantity ?? 0 }} {{ $product->unit }}</td>
        <td class="py-3 px-4"><span class="badge badge-{{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
        <td class="py-3 px-4 flex items-center gap-2">
          <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline py-1.5 px-3 text-xs">Edit</a>
          <button type="button" onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
            class="text-rust/60 hover:text-rust text-xs transition-colors">Delete</button>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="py-16 text-center text-bark-mid/40 text-sm">Walang nahanap na produkto.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-4">{{ $products->appends(request()->query())->links() }}</div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeDelete()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-sm z-10 p-6 text-center">
    <div class="w-14 h-14 rounded-full bg-rust/10 flex items-center justify-center mx-auto mb-4">
      <svg class="w-7 h-7 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </div>
    <h2 class="font-display text-xl text-bark font-medium mb-2">I-delete ang Product?</h2>
    <p class="text-bark-mid/60 text-sm mb-6">"<span id="delete-name" class="font-semibold text-bark"></span>" ay permanenteng matatanggal.</p>
    <form method="POST" id="delete-form" action="">
      @csrf @method('DELETE')
      <div class="flex gap-3">
        <button type="button" onclick="closeDelete()" class="btn-outline flex-1 justify-center">Kanselahin</button>
        <button type="submit" class="flex-1 bg-rust text-cream text-sm font-medium py-2.5 px-4 rounded-md hover:bg-rust/80 transition-colors">I-delete</button>
      </div>
    </form>
  </div>
</div>

<script>
  function confirmDelete(id, name) {
    document.getElementById('delete-name').textContent = name;
    document.getElementById('delete-form').action = '/admin/products/' + id;
    document.getElementById('delete-modal').classList.remove('hidden');
    document.getElementById('delete-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
  }
  function closeDelete() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.remove('flex');
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDelete(); });
</script>
@endsection
