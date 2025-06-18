<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('Reference to the product');
            $table->integer('book_stock')->comment('Stock recorded in the system');
            $table->integer('physical_stock')->comment('Physical stock counted');
            $table->integer('difference')->comment('Difference between physical and book stock');
            $table->text('notes')->nullable()->comment('Notes for any discrepancies');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('User who performed the opname');
            $table->timestamp('opname_date')->useCurrent()->comment('Date of stock opname');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};