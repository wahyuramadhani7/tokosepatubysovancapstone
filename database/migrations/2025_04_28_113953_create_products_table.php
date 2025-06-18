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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('qr_code')->unique()->nullable();
                $table->string('name');
                $table->string('size');
                $table->integer('stock')->default(0);
                $table->decimal('purchase_price', 10, 2);
                $table->decimal('selling_price', 10, 2);
                $table->decimal('discount_price', 10, 2)->nullable()->after('selling_price');
                $table->string('color');
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('products', 'qr_code')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->string('qr_code')->unique()->nullable()->after('id');
                });
                Log::info('Added qr_code column to products table.');
            }
            if (!Schema::hasColumn('products', 'discount_price')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->decimal('discount_price', 10, 2)->nullable()->after('selling_price');
                });
                Log::info('Added discount_price column to products table.');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'discount_price']);
        });
    }
};