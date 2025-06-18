<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('unit_code')->unique(); // Kode unik untuk setiap unit
            $table->string('qr_code')->unique(); // QR code untuk unit ini
            $table->boolean('is_active')->default(true); // Status unit (misal untuk tracking jika sudah terjual)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};