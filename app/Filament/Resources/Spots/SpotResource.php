<?php

namespace App\Filament\Resources\Spots;

use App\Filament\Resources\EnvironmentalFactors\EnvironmentalFactorResource;
use App\Filament\Resources\SpotCategories\SpotCategoryResource;
use App\Filament\Resources\Spots\Pages\ManageSpots;
use App\Filament\Resources\SpotTags\SpotTagResource;
use App\Models\EnvironmentalFactor;
use App\Models\Spot;
use App\Models\SpotCategory;
use App\Models\SpotTag;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return Spot::count();
    }

    public static function getModelLabel(): string
    {
        return __('admin.labels.spot');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.labels.spots');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return mb_ucfirst(__('admin.navigation.spots'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('distance')
                    ->numeric()
                    ->suffix(' km'),
                Select::make('tag_ids')
                    ->label('Tags')
                    ->options(static fn () => SpotTag::pluck('name', 'id'))
                    ->createOptionForm(function (Schema $schema) {
                        return SpotTagResource::form($schema);
                    })
                    ->createOptionAction(function (Action $action) {
                        return $action->modalWidth(Width::Medium);
                    })
                    ->multiple()
                    ->preload(),
                Select::make('category_ids')
                    ->label('Categories')
                    ->options(static fn () => SpotCategory::pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->createOptionForm(function (Schema $schema) {
                        return SpotCategoryResource::form($schema);
                    })
                    ->createOptionAction(function (Action $action) {
                        return $action->modalWidth(Width::Medium);
                    }),
                Select::make('environmental_factor_ids')
                    ->label('Environmental factors')
                    ->options(static fn () => EnvironmentalFactor::pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->createOptionForm(function (Schema $schema) {
                        return EnvironmentalFactorResource::form($schema);
                    })
                    ->createOptionAction(function (Action $action) {
                        return $action->modalWidth(Width::Medium);
                    }),
                FileUpload::make('images')
                    ->columnSpanFull()
                    ->multiple()
                    ->openable()
                    ->downloadable()
                    ->disk('public')
                    ->image(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('access')
                    ->columnSpanFull(),
                Textarea::make('difficulty')
                    ->columnSpanFull(),
                Repeater::make('urls')
                    ->columnSpanFull()
                    ->minItems(1)
                    ->addActionLabel(__('admin.spots.add-url'))
                    ->simple(
                        Textarea::make('url'),
                    ),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('distance')
                    ->numeric()
                    ->suffix(' km'),
                TextEntry::make('tags.name')
                    ->label('Tags')
                    ->badge(),
                TextEntry::make('categories.name')
                    ->label('Categories')
                    ->badge(),
                TextEntry::make('environmental_factors.name')
                    ->label('Environmental factors')
                    ->badge(),
                ImageEntry::make('images')
                    ->disk('public')
                    ->columnSpanFull(),
                RepeatableEntry::make('urls')
                    ->schema([
                        TextEntry::make('url')
                            ->url(static fn (?string $state): ?string => $state, true)
                            ->color(Color::Blue)
                            ->limit(48),
                    ]),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('access')
                    ->columnSpanFull(),
                TextEntry::make('difficulty')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Spot $record): bool => $record->trashed()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('distance')
                    ->numeric()
                    ->sortable()
                    ->suffix(' km'),
                TextColumn::make('tags.name')
                    ->badge(),
                TextColumn::make('categories.name')
                    ->badge(),
                TextColumn::make('environmentalFactors.name')
                    ->badge(),
                ImageColumn::make('images')
                    ->disk('public')
                    ->imageSize(100),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('access')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('difficulty')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('distance')
                    ->schema([
                        TextInput::make('distance')
                            ->numeric()
                            ->suffix(' km'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $distance = $data['distance'];

                        return $query
                            ->when(
                                filled($distance),
                                function (Builder $query) use ($distance) {
                                    return $query->where('distance', '<=', $distance);
                                });

                    }),
                SelectFilter::make('tag_ids')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('category_ids')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('environmental_factor_ids')
                    ->label('Environmental factors')
                    ->relationship('environmentalFactors', 'name')
                    ->multiple()
                    ->preload(),
                TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->iconButton(),
                ActionGroup::make([
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
                    ->color(Color::Red),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSpots::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
