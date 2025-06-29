<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create or modify products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('size');
                $table->string('color');
                $table->decimal('selling_price', 10, 2);
                $table->decimal('discount_price', 10, 2)->nullable();
                $table->timestamps();
            });
            Log::info('Created products table.');
        } else {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'qr_code')) {
                    $table->dropColumn('qr_code');
                    Log::info('Dropped qr_code column from products table.');
                }
                if (Schema::hasColumn('products', 'stock')) {
                    $table->dropColumn('stock');
                    Log::info('Dropped stock column from products table.');
                }
                if (!Schema::hasColumn('products', 'discount_price')) {
                    $table->decimal('discount_price', 10, 2)->nullable()->after('selling_price');
                    Log::info('Added discount_price column to products table.');
                }
            });
        }

        // Create or modify product_units table
        if (!Schema::hasTable('product_units')) {
            Schema::create('product_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('unit_code')->unique();
                $table->string('qr_code')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            Log::info('Created product_units table.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('products');
    }
};