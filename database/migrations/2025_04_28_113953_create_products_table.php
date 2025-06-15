<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists before creating or modifying
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('size');
                $table->integer('stock')->default(0);
                $table->integer('physical_stock')->default(0); // Set default 0
                $table->decimal('purchase_price', 10, 2);
                $table->decimal('selling_price', 10, 2);
                $table->string('color');
                $table->timestamps();
            });
        } else {
            // Check if physical_stock column exists and modify if needed
            if (Schema::hasColumn('products', 'physical_stock')) {
                try {
                    Schema::table('products', function (Blueprint $table) {
                        $table->integer('physical_stock')->default(0)->change();
                    });
                } catch (\Exception $e) {
                    Log::warning('Failed to modify physical_stock column: ' . $e->getMessage());
                    // Fallback: Run raw SQL to ensure compatibility with older MySQL versions
                    DB::statement("ALTER TABLE products MODIFY physical_stock INTEGER DEFAULT 0");
                }
            } else {
                Schema::table('products', function (Blueprint $table) {
                    $table->integer('physical_stock')->default(0);
                });
            }
        }

        // Update existing records to ensure physical_stock is set to stock or 0
        try {
            DB::statement("UPDATE products SET physical_stock = COALESCE(stock, 0) WHERE physical_stock IS NULL OR physical_stock = 0");
            $updatedCount = DB::table('products')->where('physical_stock', '>', 0)->count();
            Log::info("Successfully updated physical_stock for $updatedCount products.");
        } catch (\Exception $e) {
            Log::error('Error updating physical_stock: ' . $e->getMessage());
            throw new \Exception('Failed to update physical_stock data: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('physical_stock');
        });
    }
};