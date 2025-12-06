<?php

namespace App\Filament\Resources\Spots\Pages;

use App\Enums\User\Role;
use App\Filament\Imports\SpotImporter;
use App\Filament\Resources\Spots\SpotResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ManageRecords;

class ManageSpots extends ManageRecords
{
    protected static string $resource = SpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make('import-spots')
                ->label('Import spots')
                ->translateLabel()
                ->importer(SpotImporter::class)
                ->visible(static fn () => Filament::auth()->user()->role === Role::Admin),
        ];
    }
}
