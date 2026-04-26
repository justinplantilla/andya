<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── CUSTOMER USERS ──
        $customerIds = [];
        $customers = [
            ['name' => 'Maria Santos',   'email' => 'maria@email.com'],
            ['name' => 'Jose Reyes',     'email' => 'jose@email.com'],
            ['name' => 'Ana Cruz',       'email' => 'ana@email.com'],
        ];
        foreach ($customers as $c) {
            $customerIds[] = DB::table('users')->insertGetId([
                'name'              => $c['name'],
                'email'             => $c['email'],
                'role'              => 'customer',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // ── CATEGORIES ──
        $catIds = [];
        $categories = ['Kakanin', 'Inumin', 'Pampalasa', 'Prutas', 'Gulay'];
        foreach ($categories as $cat) {
            $catIds[] = DB::table('categories')->insertGetId([
                'name'        => $cat,
                'description' => 'Mga ' . $cat . ' na gawa sa Pilipinas.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // ── PRODUCTS ──
        $products = [
            ['name' => 'Bibingka',        'category' => 0, 'price' => 45.00,  'unit' => 'pcs',  'stock' => 50],
            ['name' => 'Puto',            'category' => 0, 'price' => 25.00,  'unit' => 'pcs',  'stock' => 80],
            ['name' => 'Suman',           'category' => 0, 'price' => 30.00,  'unit' => 'pcs',  'stock' => 60],
            ['name' => 'Buko Juice',      'category' => 1, 'price' => 35.00,  'unit' => 'btl',  'stock' => 100],
            ['name' => 'Salabat',         'category' => 1, 'price' => 20.00,  'unit' => 'sachet','stock' => 8],
            ['name' => 'Bagoong',         'category' => 2, 'price' => 55.00,  'unit' => 'jar',  'stock' => 40],
            ['name' => 'Patis',           'category' => 2, 'price' => 40.00,  'unit' => 'btl',  'stock' => 5],
            ['name' => 'Mangga',          'category' => 3, 'price' => 80.00,  'unit' => 'kg',   'stock' => 30],
            ['name' => 'Lansones',        'category' => 3, 'price' => 120.00, 'unit' => 'kg',   'stock' => 20],
            ['name' => 'Kangkong',        'category' => 4, 'price' => 15.00,  'unit' => 'bundle','stock' => 3],
        ];

        $productIds = [];
        foreach ($products as $p) {
            $productId = DB::table('products')->insertGetId([
                'category_id' => $catIds[$p['category']],
                'name'        => $p['name'],
                'description' => 'Sariwang ' . $p['name'] . ' mula sa mga lokal na magsasaka.',
                'price'       => $p['price'],
                'unit'        => $p['unit'],
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $productIds[] = $productId;

            // Inventory per product
            DB::table('inventory')->insert([
                'product_id'          => $productId,
                'quantity'            => $p['stock'],
                'low_stock_threshold' => 10,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        // ── SUPPLIERS ──
        $supplierIds = [];
        $suppliers = [
            ['name' => 'Magsasaka ni Lolo Ben',  'email' => 'loloben@farm.com',  'phone' => '09171234567', 'address' => 'Batangas'],
            ['name' => 'Andaya Farm Supplies',   'email' => 'andayafarm@email.com','phone' => '09281234567','address' => 'Laguna'],
        ];
        foreach ($suppliers as $s) {
            $supplierIds[] = DB::table('suppliers')->insertGetId([
                'name'       => $s['name'],
                'email'      => $s['email'],
                'phone'      => $s['phone'],
                'address'    => $s['address'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── PURCHASES ──
        $purchaseStatuses = ['received', 'pending', 'received'];
        foreach ($purchaseStatuses as $i => $status) {
            $purchaseId = DB::table('purchases')->insertGetId([
                'purchase_number' => 'PUR-' . strtoupper(uniqid()),
                'supplier_id'     => $supplierIds[$i % 2],
                'total_cost'      => 0,
                'status'          => $status,
                'purchased_at'    => now()->subDays($i * 3),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $totalCost = 0;
            $items = array_slice($productIds, $i * 2, 3);
            foreach ($items as $pid) {
                $qty  = rand(5, 20);
                $cost = rand(10, 50);
                DB::table('purchase_items')->insert([
                    'purchase_id' => $purchaseId,
                    'product_id'  => $pid,
                    'quantity'    => $qty,
                    'unit_cost'   => $cost,
                    'total_cost'  => $qty * $cost,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $totalCost += $qty * $cost;
            }

            DB::table('purchases')->where('id', $purchaseId)->update(['total_cost' => $totalCost]);
        }

        // ── ORDERS ──
        $orderStatuses = ['delivered', 'pending', 'processing', 'delivered', 'cancelled'];
        foreach ($orderStatuses as $i => $status) {
            $userId  = $customerIds[$i % count($customerIds)];
            $orderId = DB::table('orders')->insertGetId([
                'order_number'     => 'ORD-' . strtoupper(uniqid()),
                'user_id'          => $userId,
                'total_amount'     => 0,
                'status'           => $status,
                'shipping_address' => 'Brgy. Sample, Lungsod, Pilipinas',
                'created_at'       => now()->subDays($i * 2),
                'updated_at'       => now(),
            ]);

            $total = 0;
            $items = array_slice($productIds, $i, 2);
            foreach ($items as $pid) {
                $qty   = rand(1, 5);
                $price = DB::table('products')->where('id', $pid)->value('price');
                DB::table('order_items')->insert([
                    'order_id'    => $orderId,
                    'product_id'  => $pid,
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'total_price' => $qty * $price,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $total += $qty * $price;
            }

            DB::table('orders')->where('id', $orderId)->update(['total_amount' => $total]);

            // Invoice per order
            DB::table('invoices')->insert([
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'order_id'       => $orderId,
                'user_id'        => $userId,
                'amount'         => $total,
                'status'         => $status === 'delivered' ? 'paid' : 'unpaid',
                'paid_at'        => $status === 'delivered' ? now()->subDays($i) : null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
