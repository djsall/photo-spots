<?php

namespace App\Enums\User;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasColor, HasLabel
{
    case Viewer = 'viewer';
    case User = 'user';
    case ContentManager = 'content-manager';

    case Admin = 'admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::Viewer => __('admin.roles.viewer'),
            self::User => __('admin.roles.user'),
            self::ContentManager => __('admin.roles.content-manager'),
            self::Admin => __('admin.roles.admin'),
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::Viewer => Color::Slate,
            self::User => Color::Cyan,
            self::ContentManager => Color::Blue,
            self::Admin => Color::Purple,
        };
    }
}
