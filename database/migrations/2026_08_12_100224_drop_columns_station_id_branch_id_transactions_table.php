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
        Schema::table('transactions', function (Blueprint $table) {
            // $table->dropIndex('transactions_branch_id_foreign'); // uncomment if branch id error migration exists
            $table->dropForeign(['branch_id']);
            $table->dropForeign('transactions_ibfk_1');
            $table->dropColumn('branch_id');
            $table->dropColumn('station_id');
            $table->string('category')->nullable()->after('code');
        });

        Schema::table('transaction_steps', function (Blueprint $table) {
            $table->dropColumn('linked_station_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('transaction_steps', function (Blueprint $table) {
            $table->unsignedBigInteger('linked_station_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('station_id')->nullable();
            $table->foreign(['station_id'], 'transactions_ibfk_1')->references(['id'])->on('stations')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('branch_id')->references(['id'])->on('branches');
        });
    }
};
