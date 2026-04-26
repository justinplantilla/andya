@extends('admin.layout')
@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Pamahalaan ang mga produkto')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif

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
          <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('I-delete ba?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-rust/60 hover:text-rust text-xs transition-colors">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="py-16 text-center text-bark-mid/40 text-sm">Walang nahanap na produkto.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-4">{{ $products->appends(request()->query())->links() }}</div>
</div>
@endsection
