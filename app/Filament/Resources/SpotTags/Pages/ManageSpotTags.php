<?php

namespace App\Filament\Resources\SpotTags\Pages;

use App\Filament\Resources\SpotTags\SpotTagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageSpotTags extends ManageRecords
{
    protected static string $resource = SpotTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth(Width::Medium),
        ];
    }
}
