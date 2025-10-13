<?php

namespace App\Filament\Resources\SpotCategories\Pages;

use App\Filament\Resources\SpotCategories\SpotCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSpotCategories extends ManageRecords
{
    protected static string $resource = SpotCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
