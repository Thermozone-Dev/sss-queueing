<?php

namespace App\Observers;

use App\Models\AppointmentConfiguration;
use App\Models\Branch;
use Illuminate\Support\Facades\Schema;

class BranchObserver
{
    public function created(Branch $branch): void
    {
        if (! Schema::hasTable('appointment_configurations')) {
            return;
        }

        $branch->appointmentConfiguration()->firstOrCreate([], AppointmentConfiguration::defaults());
    }
}
