<?php

namespace App\Filament\Imports;

use App\Models\Spot;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class SpotImporter extends Importer
{
    protected static ?string $model = Spot::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('distance')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('access'),
            ImportColumn::make('difficulty'),
            ImportColumn::make('description'),
            ImportColumn::make('rating')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('urls')
                ->multiple(';'),
        ];
    }

    public function resolveRecord(): Spot
    {
        return new Spot;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your spot import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
