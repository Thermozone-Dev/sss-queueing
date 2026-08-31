<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Services\Appointment\ScheduleService;
use Tests\TestCase;

class AppointmentScheduleTest extends TestCase
{
    public function test_weekdays_are_operating_when_branch_business_day_record_is_missing(): void
    {
        $branch = new Branch();
        $service = new ScheduleService();

        $this->assertTrue($service->isOperatingDay('2026-08-31', $branch));
        $this->assertTrue($service->isOperatingDay('2026-09-01', $branch));
    }

    public function test_weekends_are_not_operating_when_branch_business_day_record_is_missing(): void
    {
        $branch = new Branch();
        $service = new ScheduleService();

        $this->assertFalse($service->isOperatingDay('2026-09-05', $branch));
        $this->assertFalse($service->isOperatingDay('2026-09-06', $branch));
    }
}
