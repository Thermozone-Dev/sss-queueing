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
        Schema::table('transaction_steps', function (Blueprint $table) {
            $table->foreign(['transaction_id'], 'transaction_steps_ibfk_1')->references(['id'])->on('transactions')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_steps', function (Blueprint $table) {
            $table->dropForeign('transaction_steps_ibfk_1');
        });
    }
};
