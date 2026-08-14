<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_queue_allowed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches', 'id', 'fk_branch_queue_allowed_branch_id')->cascadeOnDelete();
            $table->foreignId('queue_type_id')->constrained('queue_types', 'id', 'fk_branch_queue_allowed_queue_type_id')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['branch_id', 'queue_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_queue_allowed');
    }
};
