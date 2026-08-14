<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches', 'id', 'fk_branch_transactions_branch_id')->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions', 'id', 'fk_branch_transactions_transaction_id')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['branch_id', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_transactions');
    }
};
