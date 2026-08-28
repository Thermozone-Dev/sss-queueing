<?php

namespace App\Services\Appointment;

use App\Models\Branch;
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
        $day = Carbon::parse($date)->format('l');

        return (bool) optional($branch->businessDay)->{strtolower($day)};
    }

    /**
     * Generate available time slots.
     */
    public function generateTimeSlots(Branch $branch): array
    {
        $start = $branch->opening_hours;
        $end = $branch->closing_hours;

        if (!$start || !$end) {
            return [];
        }

        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);

        $timeSlots = [];

        while ($startTime < $endTime) {
            $time = $startTime->format('H:i');

            $timeSlots[] = [
                'value' => $time,
                'label' => $startTime->format('h:i A'),
                'available' => true,
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
