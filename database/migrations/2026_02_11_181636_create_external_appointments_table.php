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
        Schema::create('external_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('status')->default(1);
            $table->string('appointment_code');
            $table->date('date');
            $table->time('time');
            $table->mediumText('raw_response')->nullable();
            $table->timestamps();
        });

        Schema::table('queues', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->default(1);
            $table->unsignedBigInteger('external_appointments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_appointments');
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn('branch_id');
            $table->dropColumn('external_appointments');
        });
    }
};
