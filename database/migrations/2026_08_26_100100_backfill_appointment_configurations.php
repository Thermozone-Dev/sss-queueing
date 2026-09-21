<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $timeIntervalId = DB::table('time_intervals')->where('minutes', 30)->value('id');
        $cancellationCutoffId = DB::table('cancellation_cutoffs')->where('hours', 24)->value('id');

        $missing = DB::table('branches')
            ->whereNull('deleted_at')
            ->whereNotIn('id', DB::table('appointment_configurations')->pluck('branch_id'))
            ->pluck('id');

        foreach ($missing as $branchId) {
            DB::table('appointment_configurations')->insert([
                'branch_id' => $branchId,
                'time_interval_id' => $timeIntervalId,
                'capacity_per_interval' => 5,
                'grace_period_minutes' => 15,
                'rebooking_rule' => 'next_business_day',
                'cancellation_cutoff_id' => $cancellationCutoffId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Backfill only; nothing to reverse.
    }
};
