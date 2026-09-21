<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('time_interval_id')
                ->constrained()
                ->restrictOnDelete();
            $table->unsignedSmallInteger('capacity_per_interval')->default(5);
            $table->unsignedSmallInteger('grace_period_minutes')->default(15);
            $table->string('rebooking_rule')->default('next_business_day');
            $table->foreignId('cancellation_cutoff_id')
                ->constrained()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_configurations');
    }
};
