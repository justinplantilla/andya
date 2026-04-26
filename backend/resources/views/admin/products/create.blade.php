@extends('admin.layout')
@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Magdagdag ng bagong produkto')

@section('content')
<div class="max-w-2xl">
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-8">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="flex flex-col gap-5">
      @csrf
      @if($errors->any())
        <div class="text-rust text-sm bg-rust/10 py-3 px-4 rounded">{{ $errors->first() }}</div>
      @endif

      <div class="grid grid-cols-2 gap-5">
        <div class="col-span-2 flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Product Name</label>
          <input type="text" name="name" class="input-field" value="{{ old('name') }}" placeholder="Pangalan ng produkto" required/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Category</label>
          <select name="category_id" class="input-field" required>
            <option value="">Pumili ng category</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Price (₱)</label>
          <input type="number" name="price" class="input-field" value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0" required/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Stock Quantity</label>
          <input type="number" name="stock" class="input-field" value="{{ old('stock', 0) }}" min="0" required/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Low Stock Alert</label>
          <input type="number" name="low_stock_threshold" class="input-field" value="{{ old('low_stock_threshold', 10) }}" min="1"/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Unit</label>
          <input type="text" name="unit" class="input-field" value="{{ old('unit', 'pcs') }}" placeholder="pcs, kg, box..."/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Status</label>
          <select name="status" class="input-field">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="col-span-2 flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Description</label>
          <textarea name="description" rows="3" class="input-field resize-none" placeholder="Deskripsyon ng produkto...">{{ old('description') }}</textarea>
        </div>
        <div class="col-span-2 flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Product Image</label>
          <input type="file" name="image" class="input-field" accept="image/*"/>
        </div>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold">Save Product</button>
        <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
