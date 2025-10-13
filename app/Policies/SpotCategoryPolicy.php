<?php

namespace App\Policies;

use App\Enums\User\Role;
use App\Models\SpotCategory;
use App\Models\User;

class SpotCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SpotCategory $spot): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function update(User $user, SpotCategory $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function delete(User $user, SpotCategory $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function restore(User $user, SpotCategory $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function forceDelete(User $user, SpotCategory $spot): bool
    {
        return $user->role === Role::Admin;
    }
}
