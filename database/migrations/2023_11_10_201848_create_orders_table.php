<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costumer_id')->nullable()->constrained('costumers')->nullOnDelete();
            $table->foreignId('jewelry_id')->nullable()->constrained('jewelries')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->text('remarks');
            $table->float('weight');
            $table->integer('cost')->default(0);
            $table->json('saved_price');
            $table->integer('total_price')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->date('date_taken')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
