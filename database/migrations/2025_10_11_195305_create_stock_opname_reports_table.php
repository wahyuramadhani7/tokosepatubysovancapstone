<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOpnameReportsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_opname_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->integer('system_stock')->default(0);
            $table->integer('physical_stock')->default(0);
            $table->integer('difference')->default(0);
            $table->json('scanned_qr_codes')->nullable();
            $table->json('unscanned_qr_codes')->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opname_reports');
    }
}