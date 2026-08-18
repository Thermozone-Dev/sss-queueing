<?php

use App\Models\Branch;
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


        Schema::table('branches', function (Blueprint $table) {
            $table->softDeletes();
        });

        $default_branch = Branch::firstOrCreate(
            ['name' => 'Default Branch'],
        );
        $default_branch_id = $default_branch->id;

        $tables = [
            'users',
            'stations',
            'transactions',
        ];

        foreach ($tables as $table) {
            DB::table($table)
                ->whereNull('branch_id')
                ->update(['branch_id' => $default_branch_id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('branches', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
