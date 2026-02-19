<?php

namespace App\Filament\Resources\Spots\Actions;

use App\Models\Spot;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\URL;

class CreateTemporaryUrlAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->name('make-temporary-url');

        $this->icon(Heroicon::Link);

        $this->label(__('admin.actions.spots.temporary-url.heading'));
        $this->modalHeading(__('admin.actions.spots.temporary-url.heading'));
        $this->color(Color::Blue);
        $this->modalWidth(Width::Large);

        $this->schema([
            TextEntry::make('url')
                ->label(__('admin.actions.spots.temporary-url.click-to-copy'))
                ->hint(__('admin.actions.spots.temporary-url.valid-until'))
                ->copyable()
                ->color(Color::Blue)
                ->state(function (Spot $record) {
                    return URL::temporarySignedRoute(
                        'spots.view', now()->addHours(24), ['spot' => $record->id]
                    );
                }),
        ]);

        $this->modalSubmitAction(false);
        $this->modalCancelAction(false);
    }
}
