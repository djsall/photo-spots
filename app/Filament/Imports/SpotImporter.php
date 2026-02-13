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
                ->rules(['required'])
                ->example('Hegyi berkenyés'),
            ImportColumn::make('distance')
                ->numeric()
                ->rules(['integer'])
                ->example(25),
            ImportColumn::make('access')
                ->example('Autóval, majd minimál séta'),
            ImportColumn::make('difficulty')
                ->example('Nedves időben dagonya'),
            ImportColumn::make('description')
                ->example('Szép, ferde fa'),
            ImportColumn::make('rating')
                ->numeric()
                ->rules(['integer'])
                ->example(3),
            ImportColumn::make('urls')
                ->multiple()
                ->example('https://www.google.com/,https://www.youtube.com/'),
            ImportColumn::make('images')
                ->multiple(';')
                ->example('hely_1.jpg,hely-2.jpg,hely3.jpg'),
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
