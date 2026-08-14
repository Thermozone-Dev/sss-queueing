<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_disabled_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches', 'id', 'fk_branch_disabled_dates_branch_id')->cascadeOnDelete();
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['branch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_disabled_dates');
    }
};
