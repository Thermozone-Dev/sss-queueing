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
        Schema::create('station_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('user_id', 36)->index('station_user_user_id_foreign');
            $table->unsignedBigInteger('station_id')->index('station_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_user');
    }
};
