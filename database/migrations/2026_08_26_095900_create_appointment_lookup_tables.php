<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_intervals', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('minutes')->unique();
            $table->string('label');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cancellation_cutoffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('hours')->unique();
            $table->string('label');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('time_intervals')->insert([
            ['minutes' => 30, 'label' => '30 minutes', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['minutes' => 60, 'label' => '1 hour', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['minutes' => 180, 'label' => '3 hours', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('cancellation_cutoffs')->insert([
            ['hours' => 24, 'label' => '1 day before', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['hours' => 12, 'label' => '12 hours before', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['hours' => 6, 'label' => '6 hours before', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['hours' => 3, 'label' => '3 hours before', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cancellation_cutoffs');
        Schema::dropIfExists('time_intervals');
    }
};
