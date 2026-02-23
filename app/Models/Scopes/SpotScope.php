<?php

namespace App\Models\Scopes;

use App\Enums\User\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SpotScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! Filament::auth()->check()) {
            return;
        }

        /** @var User $user */
        $user = Filament::auth()->user();

        if ($user->role === Role::Viewer) {
            $builder->where('user_id', $user->id);
        }
    }
}
