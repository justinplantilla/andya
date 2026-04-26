@extends('admin.layout')
@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-subtitle', 'Pamahalaan ang mga kategorya')

@section('content')
@if(session('success'))
  <div class="mb-4 text-sage text-sm bg-sage/10 py-3 px-4 rounded-lg">{{ session('success') }}</div>
@endif

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
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('I-delete ba ang category na ito?')">
              @csrf @method('DELETE')
              <button type="submit" class="text-xs text-rust/60 hover:text-rust transition-colors">Delete</button>
            </form>
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
@endsection
