@extends('admin.layout')
@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-subtitle', 'Pamahalaan ang mga kategorya')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- Add Category -->
  <div class="bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Bagong Category</h2>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-col gap-4">
      @csrf
      @if($errors->any())
        <div class="text-rust text-sm bg-rust/10 py-2 px-3 rounded">{{ $errors->first() }}</div>
      @endif
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Category Name</label>
        <input type="text" name="name" class="input-field" placeholder="Pangalan ng kategorya" value="{{ old('name') }}" required/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Description</label>
        <textarea name="description" rows="3" class="input-field resize-none" placeholder="Deskripsyon...">{{ old('description') }}</textarea>
      </div>
      <button type="submit" class="btn-gold">Save Category</button>
    </form>
  </div>

  <!-- Categories List -->
  <div class="lg:col-span-2 bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/15 p-6">
    <h2 class="font-display text-xl text-bark font-medium mb-5">Lahat ng Categories</h2>
    <table class="w-full">
      <thead>
        <tr class="border-b border-gold/15">
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">#</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Name</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Products</th>
          <th class="text-left py-3 px-4 text-xs tracking-widest uppercase text-bark-mid/50 font-medium">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
        <tr class="table-row">
          <td class="py-3 px-4 text-sm text-bark-mid/50">{{ $loop->iteration }}</td>
          <td class="py-3 px-4 text-sm text-bark font-medium">{{ $category->name }}</td>
          <td class="py-3 px-4 text-sm text-bark-mid/70">{{ $category->products_count }} products</td>
          <td class="py-3 px-4 flex items-center gap-3">
            <button onclick="openEdit({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')"
              class="text-xs text-gold hover:text-rust transition-colors">Edit</button>
            <button type="button" onclick="confirmDeleteCat({{ $category->id }}, '{{ addslashes($category->name) }}')"
              class="text-xs text-rust/60 hover:text-rust transition-colors">Delete</button>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" class="py-16 text-center text-bark-mid/40 text-sm">Wala pang mga kategorya.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeEdit()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-sm z-10 p-6">
    <div class="absolute top-3 left-3 w-4 h-4 border-t border-l border-gold/30"></div>
    <div class="absolute top-3 right-3 w-4 h-4 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-3 left-3 w-4 h-4 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-3 right-3 w-4 h-4 border-b border-r border-gold/30"></div>

    <h2 class="font-display text-xl text-bark font-medium mb-5">Edit Category</h2>
    <form method="POST" id="edit-form" action="" class="flex flex-col gap-4">
      @csrf @method('PUT')
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Category Name</label>
        <input type="text" name="name" id="edit-name" class="input-field" required/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-xs tracking-widest uppercase text-bark-mid/60 font-medium">Description</label>
        <textarea name="description" id="edit-description" rows="3" class="input-field resize-none"></textarea>
      </div>
      <div class="flex gap-3">
        <button type="button" onclick="closeEdit()" class="btn-outline flex-1 justify-center">Cancel</button>
        <button type="submit" class="btn-gold flex-1 justify-center">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openEdit(id, name, description) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-form').action = '/admin/categories/' + id;
    document.getElementById('edit-modal').classList.remove('hidden');
    document.getElementById('edit-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function closeEdit() {
    document.getElementById('edit-modal').classList.add('hidden');
    document.getElementById('edit-modal').classList.remove('flex');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEdit(); });
</script>

<!-- Delete Category Modal -->
<div id="delete-cat-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-bark/60 backdrop-blur-sm" onclick="closeDeleteCat()"></div>
  <div class="relative bg-gradient-to-br from-cream to-cream-dark rounded-2xl border border-gold/20 shadow-2xl w-full max-w-sm z-10 p-6 text-center">
    <div class="w-14 h-14 rounded-full bg-rust/10 flex items-center justify-center mx-auto mb-4">
      <svg class="w-7 h-7 text-rust" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </div>
    <h2 class="font-display text-xl text-bark font-medium mb-2">I-delete ang Category?</h2>
    <p class="text-bark-mid/60 text-sm mb-6">"<span id="delete-cat-name" class="font-semibold text-bark"></span>" ay permanenteng matatanggal.</p>
    <form method="POST" id="delete-cat-form" action="">
      @csrf @method('DELETE')
      <div class="flex gap-3">
        <button type="button" onclick="closeDeleteCat()" class="btn-outline flex-1 justify-center">Kanselahin</button>
        <button type="submit" class="flex-1 bg-rust text-cream text-sm font-medium py-2.5 px-4 rounded-md hover:bg-rust/80 transition-colors">I-delete</button>
      </div>
    </form>
  </div>
</div>

<script>
  function confirmDeleteCat(id, name) {
    document.getElementById('delete-cat-name').textContent = name;
    document.getElementById('delete-cat-form').action = '/admin/categories/' + id;
    document.getElementById('delete-cat-modal').classList.remove('hidden');
    document.getElementById('delete-cat-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
  }
  function closeDeleteCat() {
    document.getElementById('delete-cat-modal').classList.add('hidden');
    document.getElementById('delete-cat-modal').classList.remove('flex');
    document.body.style.overflow = '';
  }
</script>
@endsection
