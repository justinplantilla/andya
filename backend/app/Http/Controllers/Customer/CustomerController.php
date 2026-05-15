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
use App\Models\Review;

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
            ->paginate(12)->withQueryString();

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
        if ($request->expectsJson()) return response()->json(['ok' => true]);
        return back();
    }

    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);
        if ($request->expectsJson()) return response()->json(['ok' => true]);
        return back();
    }

    public function orders(Request $request)
    {
        if ($request->ajax() && $request->suggest) {
            $query = Order::where('user_id', Auth::id());
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('order_number', 'like', '%'.$request->search.'%')
                      ->orWhereHas('orderItems.product', fn($p) => $p->where('name', 'like', '%'.$request->search.'%'));
                });
            }
            return response()->json($query->limit(6)->pluck('order_number'));
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product.category'])
            ->when($request->search, fn($q) => $q->where(function($q2) use ($request) {
                $q2->where('order_number', 'like', '%'.$request->search.'%')
                   ->orWhereHas('orderItems.product', fn($p) => $p->where('name', 'like', '%'.$request->search.'%'));
            }))
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
            'payment_method'   => 'required|in:cod',
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
            'payment_method'   => 'cod',
            'gcash_number'     => null,
            'gcash_screenshot' => null,
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

        return redirect()->route('customer.orders.confirmation', $order->id)
            ->with('success', 'Order placed successfully!');
    }

    public function directOrder(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod',
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
            'payment_method'   => 'cod',
            'gcash_number'     => null,
            'gcash_screenshot' => null,
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

        return redirect()->route('customer.orders.confirmation', $order->id)
            ->with('success', 'Order placed successfully!');
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

        request()->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        $order->update([
            'status'        => 'cancelled',
            'cancel_reason' => request('cancel_reason'),
        ]);
        $order->invoice()->update(['status' => 'cancelled']);

        // Restore inventory
        foreach ($order->orderItems as $item) {
            $inventory = \App\Models\Inventory::where('product_id', $item->product_id)->first();
            if ($inventory) {
                $inventory->increment('quantity', $item->quantity);
            }
        }

        return redirect()->route('customer.orders')->with('success', 'Na-cancel na ang order ' . $order->order_number . '.');
    }

    public function reorder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('orderItems.product')
            ->firstOrFail();

        $cart = session('cart', []);

        foreach ($order->orderItems as $item) {
            if (!$item->product || $item->product->status !== 'active') continue;
            $pid = $item->product_id;
            if (isset($cart[$pid])) {
                $cart[$pid]['quantity'] += $item->quantity;
            } else {
                $cart[$pid] = [
                    'product_id' => $item->product->id,
                    'name'       => $item->product->name,
                    'price'      => $item->product->price,
                    'unit'       => $item->product->unit,
                    'image'      => $item->product->image,
                    'quantity'   => $item->quantity,
                ];
            }
        }

        session(['cart' => $cart]);
        return redirect()->route('customer.cart')->with('success', 'Naidagdag na sa cart ang mga item mula sa order ' . $order->order_number . '.');
    }

    public function storeReview(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'delivered') {
            abort(403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:500',
        ]);

        Review::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id, 'order_id' => $order->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return back()->with('success', 'Salamat sa iyong review!');
    }

    public function showConfirmation($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['orderItems.product', 'invoice'])
            ->firstOrFail();
        return view('customer.orders.confirmation', compact('order'));
    }

    public function chatbot(Request $request)
    {
        $raw    = trim($request->input('message', ''));
        $msg    = strtolower($raw);
        $userId = Auth::id();
        $user   = Auth::user();

        // ── BAD WORDS FILTER ──
        $badWords = [
            'putang','putangina','puta','gago','gaga','bobo','boba','tanga','ulol','inutil',
            'leche','lintik','bwisit','pakyu','fuck','shit','bitch','damn','crap',
            'idiot','stupid','moron','dumbass','bastard','prick','dick','pussy','cunt',
            'tangina','tarantado','hayop','animal','engot','buang','yawa','pakshet',
        ];
        foreach ($badWords as $word) {
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $msg)) {
                return response()->json(['reply' => "⚠️ Pakiusap, gamitin ang magalang na wika. Nandito ako para tumulong sa iyo nang maayos. Subukan ulit.", 'warn' => true]);
            }
        }

        // ── ORDER TRACKING by order number ──
        if (preg_match('/ord-[a-z0-9]+/i', $msg, $match)) {
            $order = Order::where('user_id', $userId)
                ->where('order_number', strtoupper($match[0]))
                ->with(['orderItems.product', 'invoice'])
                ->first();
            if ($order) {
                $items = $order->orderItems->map(fn($i) => '• ' . $i->product->name . ' x' . $i->quantity . ' — ₱' . number_format($i->total_price, 2))->join("\n");
                $statusMsg = [
                    'pending'    => '🕐 Naghihintay pa ng confirmation mula sa admin.',
                    'confirmed'  => '✅ Nakumpirma na! Inihahanda na ang iyong order.',
                    'processing' => '⚙️ Inihahanda na para sa pagpapadala.',
                    'shipped'    => '🚚 Naka-ship na! Abangan ang delivery sa iyong address.',
                    'delivered'  => '🎉 Naihatid na! Salamat sa iyong pagbili.',
                    'cancelled'  => '❌ Na-cancel ang order na ito.',
                ][$order->status] ?? 'Hindi matukoy ang status.';
                $invoice = $order->invoice ? "Invoice: {$order->invoice->invoice_number} ({$order->invoice->status})" : '';
                $reply = "📦 {$order->order_number}\nStatus: " . ucfirst($order->status) . "\n{$statusMsg}\n\nItems:\n{$items}\n\nTotal: ₱" . number_format($order->total_amount, 2) . "\nPayment: Cash on Delivery" . ($invoice ? "\n{$invoice}" : '') . "\nShipping: {$order->shipping_address}";
            } else {
                $reply = "❌ Hindi ko mahanap ang order na '" . strtoupper($match[0]) . "'. Siguraduhing tama ang order number. I-type 'orders' para makita ang iyong mga order.";
            }
        }

        // ── MY ORDERS list ──
        elseif (preg_match('/\b(orders?|mga order|aking order|my order)\b/', $msg)) {
            $orders = Order::where('user_id', $userId)->latest()->take(5)->get();
            if ($orders->isEmpty()) {
                $reply = "Wala ka pang mga order. Pumunta sa Products page para mag-order!";
            } else {
                $list  = $orders->map(fn($o) => "• {$o->order_number} — " . ucfirst($o->status) . ' — ₱' . number_format($o->total_amount, 2))->join("\n");
                $reply = "📋 Iyong pinakabagong orders:\n{$list}\n\nI-type ang order number para sa buong detalye.";
            }
        }

        // ── STATUS-SPECIFIC ──
        elseif (str_contains($msg, 'pending')) {
            $orders = Order::where('user_id', $userId)->where('status', 'pending')->latest()->get();
            $reply  = $orders->isEmpty() ? '✅ Wala kang pending na order ngayon.'
                : "🕐 Pending orders mo:\n" . $orders->map(fn($o) => "• {$o->order_number} — ₱" . number_format($o->total_amount, 2))->join("\n");
        }
        elseif (str_contains($msg, 'confirmed') || str_contains($msg, 'nakumpirma')) {
            $orders = Order::where('user_id', $userId)->where('status', 'confirmed')->latest()->get();
            $reply  = $orders->isEmpty() ? 'Wala kang confirmed na order ngayon.'
                : "✅ Confirmed orders mo:\n" . $orders->map(fn($o) => "• {$o->order_number} — ₱" . number_format($o->total_amount, 2))->join("\n");
        }
        elseif (str_contains($msg, 'processing') || str_contains($msg, 'inihahanda')) {
            $orders = Order::where('user_id', $userId)->where('status', 'processing')->latest()->get();
            $reply  = $orders->isEmpty() ? 'Wala kang order na nasa processing ngayon.'
                : "⚙️ Processing orders mo:\n" . $orders->map(fn($o) => "• {$o->order_number} — ₱" . number_format($o->total_amount, 2))->join("\n");
        }
        elseif (str_contains($msg, 'shipped') || str_contains($msg, 'naka-ship') || str_contains($msg, 'delivery')) {
            $orders = Order::where('user_id', $userId)->where('status', 'shipped')->latest()->get();
            $reply  = $orders->isEmpty() ? 'Wala kang order na naka-ship ngayon.'
                : "🚚 Shipped orders mo:\n" . $orders->map(fn($o) => "• {$o->order_number} — ₱" . number_format($o->total_amount, 2))->join("\n") . "\n\nAbangan ang delivery sa iyong address!";
        }
        elseif (str_contains($msg, 'delivered') || str_contains($msg, 'naihatid') || str_contains($msg, 'natanggap')) {
            $orders = Order::where('user_id', $userId)->where('status', 'delivered')->latest()->get();
            $reply  = $orders->isEmpty() ? 'Wala ka pang naihatid na order.'
                : "🎉 Delivered orders mo:\n" . $orders->map(fn($o) => "• {$o->order_number} — ₱" . number_format($o->total_amount, 2))->join("\n");
        }
        elseif (str_contains($msg, 'cancel') || str_contains($msg, 'na-cancel')) {
            $orders = Order::where('user_id', $userId)->where('status', 'cancelled')->latest()->get();
            $reply  = $orders->isEmpty() ? 'Wala kang cancelled na order.'
                : "❌ Cancelled orders mo:\n" . $orders->map(fn($o) => "• {$o->order_number} — ₱" . number_format($o->total_amount, 2))->join("\n");
        }

        // ── CART ──
        elseif (str_contains($msg, 'cart') || str_contains($msg, 'basket')) {
            $cart  = session('cart', []);
            $count = count($cart);
            $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            $reply = $count === 0
                ? "Walang laman ang iyong cart. Pumunta sa Products para magdagdag ng items!"
                : "🛒 Mayroon kang {$count} item(s) sa cart na may kabuuang ₱" . number_format($total, 2) . ".\nPumunta sa Cart page para mag-checkout.";
        }

        // ── PRODUCTS ──
        elseif (str_contains($msg, 'product') || str_contains($msg, 'produkto') || str_contains($msg, 'available') || str_contains($msg, 'bilhin') || str_contains($msg, 'bibili')) {
            $products = Product::where('status', 'active')->with('category')->latest()->take(5)->get();
            if ($products->isEmpty()) {
                $reply = 'Walang available na products ngayon. Bumalik ka mamaya!';
            } else {
                $list  = $products->map(fn($p) => "• {$p->name} ({$p->category->name}) — ₱" . number_format($p->price, 2))->join("\n");
                $reply = "🛍️ Available na products:\n{$list}\n\nPumunta sa Products page para makita lahat at mag-order!";
            }
        }

        // ── INVOICE / PAYMENT ──
        elseif (str_contains($msg, 'invoice') || str_contains($msg, 'bayad') || str_contains($msg, 'payment') || str_contains($msg, 'paid')) {
            $invoices = Invoice::where('user_id', $userId)->latest()->take(5)->get();
            if ($invoices->isEmpty()) {
                $reply = 'Wala ka pang invoice.';
            } else {
                $list  = $invoices->map(fn($i) => "• {$i->invoice_number} — " . ucfirst($i->status) . ' — ₱' . number_format($i->amount, 2))->join("\n");
                $reply = "🧾 Iyong mga invoice:\n{$list}\n\nPayment method: Cash on Delivery (bayad sa pagdating ng order).";
            }
        }

        // ── PROFILE / ACCOUNT ──
        elseif (str_contains($msg, 'profile') || str_contains($msg, 'account') || str_contains($msg, 'pangalan') || str_contains($msg, 'email') || str_contains($msg, 'address')) {
            $reply = "👤 Iyong account info:\n• Pangalan: {$user->name}\n• Email: {$user->email}\n• Address: " . ($user->address ?: 'Hindi pa naka-set') . "\n\nPumunta sa Profile page para i-update ang iyong impormasyon.";
        }

        // ── HOW TO ORDER ──
        elseif (preg_match('/how.*(order|mag-order|bumili|buy)|paano.*(order|bumili|mag-order)/', $msg)) {
            $reply = "🛒 Paano mag-order:\n1. Pumunta sa Products page\n2. Piliin ang produktong gusto mo\n3. I-click ang 'Add to Cart' o 'Buy Now'\n4. Para sa Buy Now: lagyan ng quantity at address, tapos i-confirm\n5. Para sa Cart: pumili ng items, mag-checkout, lagyan ng address\n6. Payment: Cash on Delivery — babayaran mo sa pagdating ng order\n\nMatatanggap mo ang notification pagkatapos ma-confirm ng admin!";
        }

        // ── HOW TO CANCEL ──
        elseif (preg_match('/how.*(cancel)|paano.*(cancel|i-cancel)/', $msg)) {
            $reply = "❌ Paano mag-cancel ng order:\n1. Pumunta sa My Orders page\n2. Hanapin ang order na gusto mong i-cancel\n3. I-click ang 'Cancel' button (available lang sa Pending orders)\n4. Pumili ng dahilan ng cancellation\n5. I-click ang 'I-cancel'\n\n⚠️ Pwede lang mag-cancel ng Pending orders. Hindi na pwedeng i-cancel ang Confirmed, Processing, o Shipped na orders.";
        }

        // ── HOW TO REGISTER / LOGIN ──
        elseif (preg_match('/register|sign.?up|mag-register|gumawa ng account/', $msg)) {
            $reply = "📝 Paano mag-register:\n1. Pumunta sa /register page\n2. Ilagay ang iyong pangalan, email, at password\n3. Mag-click ng 'Register'\n4. May ipapadala na verification code sa iyong email\n5. Ilagay ang code para ma-verify ang iyong account\n6. Tapos na! Maaari ka nang mag-login at mag-order.";
        }
        elseif (preg_match('/login|sign.?in|mag-login|paano.*(pumasok|access)/', $msg)) {
            $reply = "🔐 Paano mag-login:\n1. Pumunta sa /login page\n2. Ilagay ang iyong email at password\n3. I-click ang 'Login'\n\nKung nakalimutan ang password:\n• I-click ang 'Forgot Password'\n• Ilagay ang iyong email\n• May ipapadala na reset code sa email mo\n• Ilagay ang code at bagong password";
        }

        // ── FORGOT PASSWORD ──
        elseif (preg_match('/forgot|nakalimutan|password|reset/', $msg)) {
            $reply = "🔑 Nakalimutan ang password?\n1. Pumunta sa Login page\n2. I-click ang 'Forgot Password'\n3. Ilagay ang iyong email address\n4. May ipapadala na reset code sa email mo\n5. Ilagay ang code at bagong password\n6. Tapos na! Maaari ka nang mag-login gamit ang bagong password.";
        }

        // ── STORE INFO ──
        elseif (preg_match('/store|shop|andaya|tindahan|contact|saan|address ng (tindahan|store)/', $msg)) {
            $storeName    = Setting::get('store_name', "Andaya's Native Products");
            $storeEmail   = Setting::get('store_email', 'N/A');
            $storePhone   = Setting::get('store_phone', 'N/A');
            $storeAddress = Setting::get('store_address', 'N/A');
            $reply = "🏪 {$storeName}\n📧 Email: {$storeEmail}\n📞 Phone: {$storePhone}\n📍 Address: {$storeAddress}\n\nNative products mula sa puso ng Pilipinas! 🇵🇭";
        }

        // ── NOTIFICATIONS ──
        elseif (str_contains($msg, 'notification') || str_contains($msg, 'abiso')) {
            $unread = CustomerNotification::where('user_id', $userId)->where('is_read', false)->count();
            $reply  = $unread > 0
                ? "🔔 Mayroon kang {$unread} bagong notification(s). I-click ang bell icon sa taas para makita."
                : "🔔 Wala kang bagong notifications ngayon.";
        }

        // ── SUMMARY / DASHBOARD ──
        elseif (preg_match('/summary|dashboard|overview|lahat|total/', $msg)) {
            $total     = Order::where('user_id', $userId)->count();
            $pending   = Order::where('user_id', $userId)->where('status', 'pending')->count();
            $delivered = Order::where('user_id', $userId)->where('status', 'delivered')->count();
            $cancelled = Order::where('user_id', $userId)->where('status', 'cancelled')->count();
            $cartCount = count(session('cart', []));
            $reply = "📊 Iyong summary:\n• Total Orders: {$total}\n• Pending: {$pending}\n• Delivered: {$delivered}\n• Cancelled: {$cancelled}\n• Items sa Cart: {$cartCount}";
        }

        // ── GREETINGS ──
        elseif (preg_match('/^(hi|hello|hey|kumusta|kamusta|magandang|good|musta|sup)/', $msg)) {
            $pending = Order::where('user_id', $userId)->where('status', 'pending')->count();
            $extra   = $pending > 0 ? "\n\n🕐 Paalala: Mayroon kang {$pending} pending order(s)." : '';
            $reply   = "Kamusta, {$user->name}! 👋 Ako si Andaya Bot, nandito para tumulong sa iyo.{$extra}\n\nMaaari akong tumulong sa:\n• Order tracking\n• Listahan ng orders\n• Products at presyo\n• Paano mag-order/cancel\n• Account info\n\nI-type ang 'help' para sa lahat ng commands!";
        }

        // ── THANKS ──
        elseif (preg_match('/thank|salamat|ty|maraming salamat/', $msg)) {
            $reply = "Walang anuman, {$user->name}! 😊 Lagi akong nandito para tumulong. May iba pa ba akong magagawa para sa iyo?";
        }

        // ── HELP ──
        elseif (str_contains($msg, 'help') || str_contains($msg, 'tulong') || str_contains($msg, 'commands') || str_contains($msg, 'ano')) {
            $reply = "📖 Mga available na commands:\n\n🔍 ORDER TRACKING\n• I-type ang order number (ORD-XXXXX)\n• 'orders' — lahat ng orders\n• 'pending/confirmed/processing/shipped/delivered/cancelled'\n\n🛒 SHOPPING\n• 'products' — available na products\n• 'cart' — items sa cart\n• 'how to order' — step-by-step guide\n• 'how to cancel' — paano mag-cancel\n\n👤 ACCOUNT\n• 'profile' — iyong account info\n• 'invoice' — iyong mga invoice\n• 'notifications' — unread notifications\n• 'summary' — overview ng lahat\n\nℹ️ IBA PA\n• 'store' — info ng tindahan\n• 'login' / 'register' — paano mag-login/register\n• 'forgot password' — reset ng password";
        }

        // ── DEFAULT ──
        else {
            $reply = "Hindi ko naintindihan ang '{$raw}'. 😅\nI-type ang 'help' para makita ang lahat ng available na commands, o i-type ang iyong order number (ORD-XXXXX) para i-track ang order mo.";
        }

        return response()->json(['reply' => $reply]);
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
