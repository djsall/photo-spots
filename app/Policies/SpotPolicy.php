<?php

namespace App\Policies;

use App\Enums\User\Role;
use App\Models\Spot;
use App\Models\User;

class SpotPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Spot $spot): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function update(User $user, Spot $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function delete(User $user, Spot $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function restore(User $user, Spot $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function forceDelete(User $user, Spot $spot): bool
    {
        return $user->role === Role::Admin;
    }
}
