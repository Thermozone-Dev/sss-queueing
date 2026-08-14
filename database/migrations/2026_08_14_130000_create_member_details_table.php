<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('member_id')->unique();
            $table->string('sss_number')->unique();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile_number')->nullable();
            $table->boolean('is_senior')->default(false);
            $table->boolean('is_pwd')->default(false);
            $table->string('status')->default('active');
            $table->dateTime('last_synced_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_details');
    }
};