<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter enum to include all new statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending'");

        // Remap old 'processing' to 'confirmed'
        DB::table('orders')->where('status', 'processing')->update(['status' => 'confirmed']);
    }

    public function down(): void
    {
        DB::table('orders')->whereIn('status', ['confirmed','shipped'])->update(['status' => 'processing']);
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','processing','delivered','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
