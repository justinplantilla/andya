<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\CustomerNotification;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        return view('customer.dashboard', [
            'total_orders'     => Order::where('user_id', $userId)->count(),
            'pending_orders'   => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'delivered_orders' => Order::where('user_id', $userId)->where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
            'recent_orders'    => Order::where('user_id', $userId)->latest()->take(5)->get(),
            'featured_products'=> Product::with('category')->where('status', 'active')->latest()->take(5)->get(),
        ]);
    }

    public function products(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Product::where('status', 'active')
                    ->where('name', 'like', '%'.$request->search.'%')
                    ->limit(6)->pluck('name')
            );
        }
        $products = Product::with(['category', 'inventory'])
            ->where('status', 'active')
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->search, fn($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->get();

        return view('customer.products.index', [
            'products'     => $products,
            'categories'   => Category::all(),
            'gcash_number' => Setting::get('gcash_number'),
        ]);
    }

    public function cart()
    {
        $cart         = session('cart', []);
        $total        = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $buyNowId     = session()->pull('buy_now_id');
        $gcash_number = Setting::get('gcash_number');
        return view('customer.cart.index', compact('cart', 'total', 'buyNowId', 'gcash_number'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart    = session('cart', []);
        $id      = $request->product_id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'unit'       => $product->unit,
                'image'      => $product->image,
                'quantity'   => $request->quantity ?? 1,
            ];
        }

        session(['cart' => $cart]);
        return back()->with('success', 'Naidagdag sa cart!');
    }

    public function buyNow(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $id      = $request->product_id;
        $cart    = session('cart', []);

        // Add to cart if not yet there
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'unit'       => $product->unit,
                'image'      => $product->image,
                'quantity'   => $request->quantity ?? 1,
            ];
        }
        session(['cart' => $cart]);

        // Redirect to cart with item pre-selected via session flag
        session(['buy_now_id' => $id]);
        return redirect()->route('customer.cart');
    }

    public function updateCart(Request $request)
    {
        $cart = session('cart', []);
        $id   = $request->product_id;
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, $request->quantity);
            session(['cart' => $cart]);
        }
        return back();
    }

    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);
        return back();
    }

    public function orders(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            return response()->json(
                Order::where('user_id', Auth::id())
                    ->where('order_number', 'like', '%'.$request->search.'%')
                    ->limit(6)->pluck('order_number')
            );
        }
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product.category'])
            ->when($request->search, fn($q) => $q->where('order_number', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'selected_items'   => 'required|array|min:1',
            'payment_method'   => 'required|in:cod,gcash',
            'gcash_number'     => 'required_if:payment_method,gcash|nullable|string',
        ]);

        $cart       = session('cart', []);
        $selected   = array_map('strval', $request->selected_items);
        $orderItems = array_filter($cart, fn($key) => in_array(strval($key), $selected), ARRAY_FILTER_USE_KEY);

        if (empty($orderItems)) return back()->with('error', 'Walang napiling item.');

        $user = Auth::user();
        if ($user->address !== $request->shipping_address) {
            $user->update(['address' => $request->shipping_address]);
        }

        $total = collect($orderItems)->sum(fn($item) => $item['price'] * $item['quantity']);

        $order = Order::create([
            'order_number'     => 'ORD-' . strtoupper(uniqid()),
            'user_id'          => Auth::id(),
            'total_amount'     => $total,
            'status'           => 'pending',
            'payment_method'   => $request->payment_method,
            'gcash_number'     => $request->payment_method === 'gcash' ? $request->gcash_number : null,
            'shipping_address' => $request->shipping_address,
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item['product_id'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);

            // Deduct inventory
            $inventory = \App\Models\Inventory::where('product_id', $item['product_id'])->first();
            if ($inventory) {
                $inventory->decrement('quantity', $item['quantity']);
            }
        }

        Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'order_id'       => $order->id,
            'user_id'        => Auth::id(),
            'amount'         => $total,
            'status'         => 'unpaid',
        ]);

        foreach ($selected as $key) unset($cart[$key]);
        session(['cart' => $cart]);

        Notification::create([
            'type'         => 'new_order',
            'title'        => 'Bagong Order',
            'message'      => $user->name . ' nag-order ng ₱' . number_format($total, 2) . ' — ' . $order->order_number,
            'reference_id' => $order->id,
        ]);

        return redirect()->route('customer.orders.confirmation', $order->id);
    }

    public function directOrder(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,gcash',
            'gcash_number'     => 'required_if:payment_method,gcash|nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = $request->quantity;
        $total   = $product->price * $qty;

        $user = Auth::user();
        if ($user->address !== $request->shipping_address) {
            $user->update(['address' => $request->shipping_address]);
        }

        $order = Order::create([
            'order_number'     => 'ORD-' . strtoupper(uniqid()),
            'user_id'          => Auth::id(),
            'total_amount'     => $total,
            'status'           => 'pending',
            'payment_method'   => $request->payment_method,
            'gcash_number'     => $request->payment_method === 'gcash' ? $request->gcash_number : null,
            'shipping_address' => $request->shipping_address,
        ]);

        OrderItem::create([
            'order_id'    => $order->id,
            'product_id'  => $product->id,
            'quantity'    => $qty,
            'unit_price'  => $product->price,
            'total_price' => $total,
        ]);

        // Deduct inventory
        $inventory = \App\Models\Inventory::where('product_id', $product->id)->first();
        if ($inventory) {
            $inventory->decrement('quantity', $qty);
        }

        Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'order_id'       => $order->id,
            'user_id'        => Auth::id(),
            'amount'         => $total,
            'status'         => 'unpaid',
        ]);

        Notification::create([
            'type'         => 'new_order',
            'title'        => 'Bagong Order',
            'message'      => $user->name . ' nag-order ng ₱' . number_format($total, 2) . ' — ' . $order->order_number,
            'reference_id' => $order->id,
        ]);

        return redirect()->route('customer.orders.confirmation', $order->id);
    }

    public function showOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['orderItems.product.category', 'invoice'])
            ->firstOrFail();
        return view('customer.orders.show', compact('order'));
    }

    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Hindi na pwedeng i-cancel ang order na ito.');
        }

        $order->update(['status' => 'cancelled']);
        $order->invoice()->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders')->with('success', 'Na-cancel na ang order ' . $order->order_number . '.');
    }

    public function showConfirmation($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['orderItems.product', 'invoice'])
            ->firstOrFail();
        return view('customer.orders.confirmation', compact('order'));
    }

    public function markNotificationsRead()
    {
        CustomerNotification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return back();
    }

    public function markOneNotificationRead(CustomerNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->update(['is_read' => true]);
        if ($notification->order_id) {
            return redirect()->route('customer.orders.show', $notification->order_id);
        }
        return back();
    }

    public function profile()
    {
        return view('customer.profile.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string',
        ]);
        Auth::user()->update($request->only('name', 'email', 'address'));
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mali ang current password.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password updated successfully!');
    }
}
