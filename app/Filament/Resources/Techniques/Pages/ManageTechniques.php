<?php

namespace App\Filament\Resources\Techniques\Pages;

use App\Filament\Resources\Techniques\TechniqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageTechniques extends ManageRecords
{
    protected static string $resource = TechniqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth(Width::Large),
        ];
    }
}
