@extends('admin.layout')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-subtitle', 'I-update ang produkto')

@section('content')
<div class="max-w-2xl">
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-8">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="flex flex-col gap-5">
      @csrf @method('PUT')
      @if($errors->any())
        <div class="text-rust text-sm bg-rust/10 py-3 px-4 rounded">{{ $errors->first() }}</div>
      @endif

      <div class="grid grid-cols-2 gap-5">
        <div class="col-span-2 flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Product Name</label>
          <input type="text" name="name" class="input-field" value="{{ old('name', $product->name) }}" required/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Category</label>
          <select name="category_id" class="input-field" required>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Price (₱)</label>
          <input type="number" name="price" class="input-field" value="{{ old('price', $product->price) }}" step="0.01" min="0" required/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Stock Quantity</label>
          <input type="number" name="stock" class="input-field" value="{{ old('stock', $product->inventory->quantity ?? 0) }}" min="0" required/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Low Stock Alert</label>
          <input type="number" name="low_stock_threshold" class="input-field" value="{{ old('low_stock_threshold', $product->inventory->low_stock_threshold ?? 10) }}" min="1"/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Unit</label>
          <input type="text" name="unit" class="input-field" value="{{ old('unit', $product->unit) }}"/>
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Status</label>
          <select name="status" class="input-field">
            <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <div class="col-span-2 flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Description</label>
          <textarea name="description" rows="3" class="input-field resize-none">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="col-span-2 flex flex-col gap-2">
          <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Product Image</label>
          @if($product->image)
            <div class="flex items-center gap-3 mb-2">
              <img src="{{ asset('storage/'.$product->image) }}" class="w-16 h-16 rounded-xl object-cover border border-gold/20"/>
              <span class="text-xs text-bark-mid/50">Current image</span>
            </div>
          @endif
          <input type="file" name="image" class="input-field" accept="image/*"/>
          <p class="text-xs text-bark-mid/40">Mag-upload ng bago para palitan. Iwanan kung ayaw palitan.</p>
        </div>
        <button type="submit" class="btn-gold">Update Product</button>
        <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
