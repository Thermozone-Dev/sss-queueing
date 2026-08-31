<?php

namespace App\Services\Appointment;

use App\Models\Branch;
use App\Models\Breaktime;
use App\Models\BranchDisabledDate;
use Carbon\Carbon;

class ScheduleService
{
    /**
     * Check if selected date is an operating day.
     */
    public function isOperatingDay(
        string $date,
        Branch $branch
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | Check Holiday / Disabled Date
        |--------------------------------------------------------------------------
        */

        $isDisabledDate = BranchDisabledDate::where('branch_id', $branch->id)
            ->whereDate('date', $date)
            ->exists();

        if ($isDisabledDate) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Check Business Day
        |--------------------------------------------------------------------------
        */

        $dayName = Carbon::parse($date)->format('l');
        $dayKey = strtolower($dayName);

        $businessDay = $branch->businessDay;

        if (!$businessDay) {
            return in_array(
                $dayKey,
                [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                ],
                true
            );
        }

        return (bool) $businessDay->{$dayKey};
    }

    /**
     * Check if date is a disabled / holiday date.
     */
    public function isDisabledDate(
        string $date,
        Branch $branch
    ): bool {
        return BranchDisabledDate::where('branch_id', $branch->id)
            ->whereDate('date', $date)
            ->exists();
    }

    /**
     * Generate available time slots.
     */
    public function generateTimeSlots(
        Branch $branch,
        ?string $date = null
    ): array {
        $start = $branch->opening_hours;
        $end = $branch->closing_hours;

        if (!$start || !$end) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Holiday / Disabled Date
        |--------------------------------------------------------------------------
        */

        if (
            $date &&
            $this->isDisabledDate($date, $branch)
        ) {
            return [];
        }

        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);

        /*
        |--------------------------------------------------------------------------
        | Break Times
        |--------------------------------------------------------------------------
        */

        $breakTimes = Breaktime::where('branch_id', $branch->id)
            ->get();

        $timeSlots = [];

        while ($startTime < $endTime) {
            $time = $startTime->format('H:i');

            $slotStart = $startTime->copy();
            $slotEnd = $startTime->copy()->addMinutes(30);

            /*
            |--------------------------------------------------------------------------
            | Check Break Time
            |--------------------------------------------------------------------------
            */

            $isBreaktime = $breakTimes->contains(function ($break) use (
                $slotStart,
                $slotEnd
            ) {
                $breakStart = Carbon::parse($break->from);
                $breakEnd = Carbon::parse($break->to);

                return $slotStart < $breakEnd
                    && $slotEnd > $breakStart;
            });

            $timeSlots[] = [
                'value' => $time,
                'label' => $startTime->format('h:i A'),
                'available' => !$isBreaktime,
                'reason' => $isBreaktime ? 'BREAK' : null,
            ];

            $startTime->addMinutes(30);
        }

        return $timeSlots;
    }

    /**
     * Find a time slot.
     */
    public function findTimeSlot(
        string $time,
        array $timeSlots
    ): ?array {
        return collect($timeSlots)
            ->firstWhere('value', $time);
    }
}