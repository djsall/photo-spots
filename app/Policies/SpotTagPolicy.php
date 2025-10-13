<?php

namespace App\Policies;

use App\Enums\User\Role;
use App\Models\SpotTag;
use App\Models\User;

class SpotTagPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function view(User $user, SpotTag $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function update(User $user, SpotTag $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function delete(User $user, SpotTag $spot): bool
    {
        return $user->role === Role::Admin;
    }

    public function restore(User $user, SpotTag $spot): bool
    {
        return $user->role === Role::Admin;
    }

    public function forceDelete(User $user, SpotTag $spot): bool
    {
        return $user->role === Role::Admin;
    }
}
