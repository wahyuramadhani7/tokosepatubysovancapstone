<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action')->comment('Jenis aksi: login, logout, create_transaction, update_product, dll');
            $table->string('description')->nullable()->comment('Deskripsi aksi');
            $table->string('model_type')->nullable()->comment('Tipe model yang terkait: Transaction, Product, dll');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID model yang terkait');
            $table->json('details')->nullable()->comment('Detail tambahan dalam JSON');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'action']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}