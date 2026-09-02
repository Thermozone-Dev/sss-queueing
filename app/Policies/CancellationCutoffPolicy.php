<?php

namespace App\Policies;

use App\Models\CancellationCutoff;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CancellationCutoffPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_appointment_option');
    }

    public function view(User $user, CancellationCutoff $cancellationCutoff): bool
    {
        return $user->can('view_appointment_option');
    }

    public function create(User $user): bool
    {
        return $user->can('create_appointment_option');
    }

    public function update(User $user, CancellationCutoff $cancellationCutoff): bool
    {
        return $user->can('update_appointment_option');
    }

    public function delete(User $user, CancellationCutoff $cancellationCutoff): bool
    {
        return false;
    }
}
