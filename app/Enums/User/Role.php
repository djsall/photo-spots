<?php

namespace App\Enums\User;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Role: string implements HasColor, HasLabel
{
    case Viewer = 'viewer';
    case ContentManager = 'content-manager';

    case Admin = 'admin';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Viewer => __('admin.roles.viewer'),
            self::ContentManager => __('admin.roles.content-manager'),
            self::Admin => __('admin.roles.admin'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Viewer => Color::Cyan,
            self::ContentManager => Color::Blue,
            self::Admin => Color::Purple,
        };
    }
}
