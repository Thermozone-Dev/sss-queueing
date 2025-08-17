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
        DB::table('settings')->where('id', 6)->update([
            'payload' => json_encode([
                'gray' => 'rgb(107, 114, 128)',
                'info' => 'rgb(0, 0, 255)',
                'danger' => 'rgb(199, 29, 81)',
                'primary' => 'rgb(0, 114, 54)',
                'success' => 'rgb(12, 195, 178)',
                'warning' => 'rgb(255, 186, 93)',
                'secondary' => 'rgb(134, 195, 65)',
            ]),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
