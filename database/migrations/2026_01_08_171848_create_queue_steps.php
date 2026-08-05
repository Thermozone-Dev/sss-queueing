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
        Schema::create('queue_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('queue_id');
            $table->dateTime('eligible_start_time')->nullable();
            $table->unsignedBigInteger('transaction_step_id');
            $table->unsignedBigInteger('queue_step_status_id')->default(1);
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('last_transacted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_steps');
    }
};
