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
        Schema::create('queue_steps_timestamps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('queue_id');
            $table->dateTime('first_called_at')->nullable();
            $table->dateTime('recalled_last_at')->nullable();
            $table->integer('recall_count')->default(0);
            $table->dateTime('removed_at')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('skipped_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreign('queue_id')->references('id')->on('queues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_steps_timestamps');
    }
};
