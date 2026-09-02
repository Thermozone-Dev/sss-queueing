<?php

namespace App\Policies;

use App\Models\TimeInterval;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeIntervalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_appointment_option');
    }

    public function view(User $user, TimeInterval $timeInterval): bool
    {
        return $user->can('view_appointment_option');
    }

    public function create(User $user): bool
    {
        return $user->can('create_appointment_option');
    }

    public function update(User $user, TimeInterval $timeInterval): bool
    {
        return $user->can('update_appointment_option');
    }

    public function delete(User $user, TimeInterval $timeInterval): bool
    {
        return false;
    }
}
