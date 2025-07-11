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
        Schema::create('jewelries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->nullable()->constrained('prices')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('safe_box_id')->nullable()->constrained('safe_boxes')->nullOnDelete();
            $table->string('name');
            $table->string('jewelry_code')->unique();
            $table->float('weight');
            $table->integer('cost')->default(0);
            $table->string('photo')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['SOLD', 'READY'])->default('READY');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelries');
    }
};
