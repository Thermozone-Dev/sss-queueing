<?php

namespace App\Services\Appointment;

use Carbon\Carbon;

class ScheduleService
{
    /**
     * Check if selected date is an operating day.
     */
    public function isOperatingDay(
        string $date,
        array $branch
    ): bool {
        $day = Carbon::parse($date)->format('l');

        return in_array(
            $day,
            $branch['operating_days'] ?? []
        );
    }

    /**
     * Generate available time slots.
     */
    public function generateTimeSlots(array $branch): array
    {
        $start = $branch['working_hours']['start'] ?? null;
        $end = $branch['working_hours']['end'] ?? null;

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
