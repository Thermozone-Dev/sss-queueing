<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_responses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['branch', 'transaction', 'member']);
            $table->json('payload');
            $table->string('url');
            $table->json('response');
            $table->string('method');
            $table->boolean('is_latest')->default(true);

            $table->timestamps();
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_responses');
    }
};
