<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queue_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('queue_statuses')->insert([
            [
                'id' => 1,
                'name' => 'Pending'
            ],
            [
                'id' => 2,
                'name' => 'Processing'
            ],
            [
                'id' => 3,
                'name' => 'Paused'
            ],
            [
                'id' => 4,
                'name' => 'Completed'
            ],
            [
                'id' => 5,
                'name' => 'Removed'
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_statuses');
    }
};
