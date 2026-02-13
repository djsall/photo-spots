<?php

namespace App\Filament\Resources\EnvironmentalFactors\Pages;

use App\Filament\Resources\EnvironmentalFactors\EnvironmentalFactorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageEnvironmentalFactors extends ManageRecords
{
    protected static string $resource = EnvironmentalFactorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth(Width::Large),
        ];
    }
}
