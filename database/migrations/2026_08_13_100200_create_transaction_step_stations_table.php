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
        Schema::create('transaction_step_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->onDelete('cascade');
            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->onDelete('cascade');
            $table->foreignId('transaction_step_id')
                ->constrained('transaction_steps')
                ->onDelete('cascade');
            $table->foreignId('station_id')
                ->constrained('stations')
                ->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_step_stations');
    }
};
