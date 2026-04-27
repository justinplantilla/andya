<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\User;

class AdminController extends Controller
{
    // ── DASHBOARD ──
    public function dashboard()
    {
        return view('admin.dashboard', [
            'total_products'  => Product::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'monthly_revenue' => Invoice::where('status', 'paid')
                                    ->whereMonth('paid_at', now()->month)
                                    ->sum('amount'),
            'recent_orders'   => Order::with('user')->latest()->take(5)->get(),
            'low_stock'       => Inventory::with('product')
                                    ->whereColumn('quantity', '<=', 'low_stock_threshold')
                                    ->get(),
        ]);
    }

    // ── PRODUCTS ──
    public function products(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Product::where('name', 'like', '%'.$request->search.'%')->limit(6)->pluck('name')
            );
        }
        $products = Product::with(['category', 'inventory'])
            ->when($request->search, fn($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->latest()->paginate(10);

        return view('admin.products.index', [
            'products'   => $products,
            'categories' => Category::all(),
        ]);
    }

    public function createProduct()
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'required|string',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'category_id', 'price', 'unit', 'description', 'status');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product = Product::create($data);

        Inventory::create([
            'product_id'          => $product->id,
            'quantity'            => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold ?? 10,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.edit', [
            'product'    => $product->load('inventory'),
            'categories' => Category::all(),
        ]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'required|string',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'category_id', 'price', 'unit', 'description', 'status');
        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($data);

        $product->inventory()->updateOrCreate(
            ['product_id' => $product->id],
            ['quantity' => $request->stock, 'low_stock_threshold' => $request->low_stock_threshold ?? 10]
        );

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    // ── CATEGORIES ──
    public function categories()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->latest()->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);
        Category::create($request->only('name', 'description'));
        return back()->with('success', 'Category added!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,'.$category->id]);
        $category->update($request->only('name', 'description'));
        return back()->with('success', 'Category updated!');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ── INVENTORY ──
    public function inventory(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Product::where('name', 'like', '%'.$request->search.'%')->limit(6)->pluck('name')
            );
        }
        $inventory = Inventory::with('product.category')
            ->when($request->search, fn($q) => $q->whereHas('product', fn($p) => $p->where('name', 'like', '%'.$request->search.'%')))
            ->when($request->status, function ($q) use ($request) {
                if ($request->status === 'low')  return $q->whereColumn('quantity', '<=', 'low_stock_threshold');
                if ($request->status === 'out')  return $q->where('quantity', 0);
                if ($request->status === 'in')   return $q->whereColumn('quantity', '>', 'low_stock_threshold');
            })
            ->paginate(10);

        return view('admin.inventory.index', compact('inventory'));
    }

    public function adjustInventory(Request $request, Inventory $inventory)
    {
        $request->validate([
            'action'   => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
        ]);

        match($request->action) {
            'add'      => $inventory->increment('quantity', $request->quantity),
            'subtract' => $inventory->decrement('quantity', min($request->quantity, $inventory->quantity)),
            'set'      => $inventory->update(['quantity' => $request->quantity]),
        };

        return back()->with('success', 'Stock updated!');
    }

    // ── ORDERS ──
    public function orders(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Order::where('order_number', 'like', '%'.$request->search.'%')->limit(6)->pluck('order_number')
            );
        }
        $orders = Order::with(['user', 'orderItems.product.category'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->payment_method, fn($q) => $q->where('payment_method', $request->payment_method))
            ->when($request->search, fn($q) => $q->where('order_number', 'like', '%'.$request->search.'%'))
            ->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);

        if ($request->status === 'delivered') {
            $order->invoice()->update(['status' => 'paid', 'paid_at' => now()]);
        }

        // Notify customer
        $messages = [
            'confirmed'  => 'Ang iyong order na ' . $order->order_number . ' ay na-confirm na.',
            'processing' => 'Ang iyong order na ' . $order->order_number . ' ay pinoproseso na.',
            'shipped'    => 'Ang iyong order na ' . $order->order_number . ' ay naka-ship na. Abangan!',
            'delivered'  => 'Ang iyong order na ' . $order->order_number . ' ay naihatid na. Salamat!',
            'cancelled'  => 'Ang iyong order na ' . $order->order_number . ' ay na-cancel.',
        ];
        if (isset($messages[$request->status])) {
            \App\Models\CustomerNotification::create([
                'user_id'      => $order->user_id,
                'order_id'     => $order->id,
                'type'         => 'order_update',
                'title'        => 'Order Update',
                'message'      => $messages[$request->status],
                'is_read'      => false,
            ]);
        }

        return back()->with('success', 'Order status updated!');
    }

    public function processAndPrint(Order $order)
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
        }

        $invoice = $order->invoice;
        return redirect()->route('admin.invoices.receipt', $invoice);
    }

    // ── PURCHASES ──
    public function purchases(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Purchase::where('purchase_number', 'like', '%'.$request->search.'%')->limit(6)->pluck('purchase_number')
            );
        }
        $purchases = Purchase::with(['supplier', 'purchaseItems.product'])
            ->when($request->search, fn($q) => $q->where('purchase_number', 'like', '%'.$request->search.'%'))
            ->latest()->paginate(10);

        return view('admin.purchases.index', [
            'purchases' => $purchases,
            'suppliers' => Supplier::where('status', 'active')->get(),
            'products'  => Product::with('inventory')->where('status', 'active')->get(),
        ]);
    }

    public function createPurchase()
    {
        return view('admin.purchases.create', [
            'suppliers' => Supplier::where('status', 'active')->get(),
            'products'  => Product::with('inventory')->where('status', 'active')->get(),
        ]);
    }

    public function storePurchase(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products'    => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.unit_cost'  => 'required|numeric|min:0',
        ]);

        $items = collect($request->products)->filter(fn($p) => !empty($p['product_id']));
        $total = $items->sum(fn($p) => $p['quantity'] * $p['unit_cost']);

        $purchase = Purchase::create([
            'purchase_number' => 'PUR-' . strtoupper(uniqid()),
            'supplier_id'     => $request->supplier_id,
            'total_cost'      => $total,
            'status'          => 'pending',
            'notes'           => $request->notes,
            'purchased_at'    => now(),
        ]);

        foreach ($items as $item) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id'  => $item['product_id'],
                'quantity'    => $item['quantity'],
                'unit_cost'   => $item['unit_cost'],
                'total_cost'  => $item['quantity'] * $item['unit_cost'],
            ]);
        }

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase order created!');
    }

    public function updatePurchaseStatus(Request $request, Purchase $purchase)
    {
        $request->validate(['status' => 'required|in:pending,received,cancelled']);

        $old = $purchase->status;
        $purchase->update(['status' => $request->status]);

        // Add to inventory when marked as received
        if ($request->status === 'received' && $old !== 'received') {
            foreach ($purchase->purchaseItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Purchase status updated!');
    }

    // ── CUSTOMERS ──
    public function customers(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->when($request->search, fn($q) => $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'))
            ->latest()->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    // ── INVOICES ──
    public function invoices(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Invoice::where('invoice_number', 'like', '%'.$request->search.'%')->limit(6)->pluck('invoice_number')
            );
        }
        $invoices = Invoice::with(['order.user', 'user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('invoice_number', 'like', '%'.$request->search.'%'))
            ->latest()->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function markInvoicePaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Invoice marked as paid.');
    }

    public function printReceipt(Invoice $invoice)
    {
        $invoice->load(['order.orderItems.product.category', 'user']);
        return view('admin.invoices.receipt', compact('invoice'));
    }

    public function markNotificationsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return back();
    }

    public function markOneRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        if ($notification->type === 'new_order') {
            return redirect()->route('admin.orders.index');
        }
        return back();
    }

    // ── REPORTS ──
    public function reports(Request $request)
    {
        $year  = $request->year  ?? now()->year;
        $month = $request->month ?? now()->month;

        // Monthly revenue per month this year
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill all 12 months
        $revenueByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueByMonth[$m] = $monthlyRevenue[$m] ?? 0;
        }

        // Orders summary this month
        $ordersByStatus = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Top products this month
        $topProducts = OrderItem::with('product')
            ->whereHas('order', fn($q) => $q->whereYear('created_at', $year)->whereMonth('created_at', $month))
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // Summary totals
        $totalRevenue    = Invoice::where('status', 'paid')->whereYear('paid_at', $year)->whereMonth('paid_at', $month)->sum('amount');
        $totalOrders     = Order::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $totalCustomers  = User::where('role', 'customer')->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $cancelledOrders = Order::where('status', 'cancelled')->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

        return view('admin.reports.index', compact(
            'revenueByMonth', 'ordersByStatus', 'topProducts',
            'totalRevenue', 'totalOrders', 'totalCustomers', 'cancelledOrders',
            'year', 'month'
        ));
    }

    // ── SETTINGS ──
    public function settings()
    {
        return view('admin.settings.index', [
            'gcash_number' => Setting::get('gcash_number'),
            'store_name'   => Setting::get('store_name', "Andaya's Native Products"),
            'store_email'  => Setting::get('store_email'),
            'store_phone'  => Setting::get('store_phone'),
            'store_address'=> Setting::get('store_address'),
        ]);
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'gcash_number'  => 'nullable|string|max:11',
            'store_name'    => 'nullable|string|max:255',
            'store_email'   => 'nullable|email',
            'store_phone'   => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
        ]);

        foreach (['gcash_number', 'store_name', 'store_email', 'store_phone', 'store_address'] as $key) {
            Setting::set($key, $request->input($key));
        }

        return back()->with('success', 'Settings saved!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mali ang current password.']);
        }

        Auth::user()->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        return back()->with('password_success', 'Password updated successfully!');
    }
}
