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
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_account_id')->nullable()->constrained('deposit_accounts')->nullOnDelete();
            $table->string('transaction_number')->unique();
            $table->enum('type', ['DEBIT', 'CREDIT']);
            $table->enum('category', ['MONEY', 'GOLD']);
            $table->float('weight')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('cost')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('deposit_transactions');
    }
};
