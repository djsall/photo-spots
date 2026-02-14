<?php

namespace App\Policies\Defaults;

use App\Enums\User\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Model $spot): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function update(User $user, Model $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function delete(User $user, Model $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function restore(User $user, Model $spot): bool
    {
        return in_array($user->role, [Role::ContentManager, Role::Admin]);
    }

    public function forceDelete(User $user, Model $spot): bool
    {
        return $user->role === Role::Admin;
    }
}
