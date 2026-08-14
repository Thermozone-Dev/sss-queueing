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
        Schema::create('station_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')
                ->constrained('stations')
                ->onDelete('cascade');
            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['station_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_transactions');
    }
};
