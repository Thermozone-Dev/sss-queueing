<?php

namespace App\Policies;

use App\Models\AppointmentConfiguration;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentConfigurationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_appointment_configuration');
    }

    public function view(User $user, AppointmentConfiguration $configuration): bool
    {
        return $user->can('view_appointment_configuration');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AppointmentConfiguration $configuration): bool
    {
        return $user->can('update_appointment_configuration');
    }

    public function delete(User $user, AppointmentConfiguration $configuration): bool
    {
        return false;
    }
}
