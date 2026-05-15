@extends('admin.layout')
@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Magdagdag ng bagong produkto')

@section('content')
<div class="max-w-2xl mx-auto flex flex-col gap-5">

  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="flex flex-col gap-5">
    @csrf

    @if($errors->any())
      <div class="bg-rust/10 border border-rust/20 text-rust text-sm px-4 py-3 rounded-xl">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- Basic Info --}}
    <div class="bg-white/60 border border-gold/10 rounded-2xl p-6 flex flex-col gap-4">
      <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Basic Info</p>

      <div class="flex flex-col gap-1.5">
        <label class="text-xs text-bark-mid/60">Product Name</label>
        <input type="text" name="name" class="input-field" value="{{ old('name') }}" placeholder="Pangalan ng produkto" required/>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Category</label>
          <select name="category_id" class="input-field" required>
            <option value="">Pumili ng category</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Status</label>
          <select name="status" class="input-field">
            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-xs text-bark-mid/60">Description <span class="text-bark-mid/30">(optional)</span></label>
        <textarea name="description" rows="2" class="input-field resize-none" placeholder="Maikling deskripsyon...">{{ old('description') }}</textarea>
      </div>
    </div>

    {{-- Pricing & Inventory --}}
    <div class="bg-white/60 border border-gold/10 rounded-2xl p-6 flex flex-col gap-4">
      <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Pricing & Inventory</p>

      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Price (₱)</label>
          <input type="number" name="price" class="input-field" value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0" required/>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Unit</label>
          <input type="text" name="unit" class="input-field" value="{{ old('unit', 'pcs') }}" placeholder="pcs, kg, box..."/>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Stock Quantity</label>
          <input type="number" name="stock" class="input-field" value="{{ old('stock', 0) }}" min="0" required/>
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="text-xs text-bark-mid/60">Low Stock Alert</label>
          <input type="number" name="low_stock_threshold" class="input-field" value="{{ old('low_stock_threshold', 10) }}" min="1"/>
        </div>
      </div>
    </div>

    {{-- Product Image --}}
    <div class="bg-white/60 border border-gold/10 rounded-2xl p-6 flex flex-col gap-4">
      <p class="text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Product Image</p>

      <div class="flex items-start gap-4">
        <div id="img-preview-wrap" class="w-24 h-24 rounded-xl border-2 border-dashed border-gold/30 bg-bark/3 flex items-center justify-center flex-shrink-0 overflow-hidden">
          <svg id="img-placeholder" class="w-8 h-8 text-bark/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <img id="img-preview" src="" class="w-full h-full object-cover hidden"/>
        </div>
        <div class="flex-1 flex flex-col gap-2">
          <label for="image-input" class="btn-outline cursor-pointer w-fit text-xs">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Pumili ng Larawan
          </label>
          <input type="file" name="image" id="image-input" accept="image/*" class="hidden" onchange="previewImage(this)"/>
          <p class="text-[11px] text-bark-mid/40">PNG, JPG hanggang 2MB</p>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3">
      <button type="submit" class="btn-gold">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        I-save ang Produkto
      </button>
      <a href="{{ route('admin.products.index') }}" class="btn-outline">Bumalik</a>
    </div>

  </form>
</div>

<script>
  function previewImage(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('img-preview').src = e.target.result;
      document.getElementById('img-preview').classList.remove('hidden');
      document.getElementById('img-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
  }
</script>
@endsection
