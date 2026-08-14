<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default queue types
        \DB::table('queue_types')->insert([
            ['name' => 'Walk In', 'description' => 'Walk-in queue'],
            ['name' => 'Appointment', 'description' => 'Appointment-based queue'],
            ['name' => 'Priority', 'description' => 'Priority lanes (Senior Citizens / PWD)'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_types');
    }
};
