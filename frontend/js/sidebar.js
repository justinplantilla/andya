function loadSidebar(activePage) {
  const nav = [
    { label: 'Dashboard', href: 'dashboard.html', icon: '📊' },
    { label: 'Products', href: 'products.html', icon: '📦' },
    { label: 'Categories', href: 'categories.html', icon: '🗂️' },
    { label: 'Suppliers', href: 'suppliers.html', icon: '🏭' },
    { label: 'Orders', href: 'orders.html', icon: '🛒' },
    { label: 'Transactions', href: 'transactions.html', icon: '💳' },
    { label: 'Users', href: 'users.html', icon: '👥' },
    { label: 'Reports', href: 'reports.html', icon: '📈' },
  ];

  const links = nav.map(item => {
    const isActive = item.href === activePage;
    return `
      <a href="${item.href}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
        ${isActive ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700/60'}">
        <span>${item.icon}</span>${item.label}
      </a>`;
  }).join('');

  const sidebar = `
    <aside class="fixed top-0 left-0 h-screen w-56 bg-indigo-800 flex flex-col z-40">
      <div class="px-6 py-5 border-b border-indigo-700">
        <span class="text-white font-bold text-lg tracking-wide">Andaya Admin</span>
      </div>
      <nav class="flex-1 overflow-y-auto px-3 py-4 flex flex-col gap-1">
        ${links}
      </nav>
      <div class="px-4 py-4 border-t border-indigo-700">
        <a href="../../login.html" class="flex items-center gap-2 text-indigo-200 hover:text-white text-sm">
          <span>🚪</span> Logout
        </a>
      </div>
    </aside>
    <div class="ml-56 min-h-screen flex flex-col">
      <main class="flex-1 p-6" id="main-content">`;

  const closingTags = `</main></div>`;

  document.body.insertAdjacentHTML('afterbegin', sidebar);
  document.body.insertAdjacentHTML('beforeend', closingTags);

  // Move existing body children (before sidebar) into main-content
  // Already handled by structure above
}
