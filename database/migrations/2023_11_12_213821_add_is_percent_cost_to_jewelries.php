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
        Schema::table('jewelries', function (Blueprint $table) {
            $table->boolean('is_percent_cost')->default(false)->after('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jewelries', function (Blueprint $table) {
            $table->dropColumn('is_percent_cost');
        });
    }
};
