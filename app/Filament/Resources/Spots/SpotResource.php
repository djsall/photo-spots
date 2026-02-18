<?php

namespace App\Filament\Resources\Spots;

use App\Enums\User\Role;
use App\Filament\Resources\Spots\Actions\CreateTemporaryUrlAction;
use App\Filament\Resources\Spots\Pages\ManageSpots;
use App\Models\Category;
use App\Models\EnvironmentalFactor;
use App\Models\Spot;
use App\Models\Technique;
use BackedEnum;
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
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
use Illuminate\Support\Facades\Storage;
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
                    ->label('Techniques')
                    ->options(static fn () => Technique::pluck('name', 'id'))
                    ->multiple()
                    ->preload(),
                Select::make('category_ids')
                    ->label('Categories')
                    ->options(static fn () => Category::pluck('name', 'id'))
                    ->multiple()
                    ->preload(),
                Select::make('environmental_factor_ids')
                    ->label('Environmental factors')
                    ->options(static fn () => EnvironmentalFactor::pluck('name', 'id'))
                    ->multiple()
                    ->preload(),
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
                    ->minItems(0)
                    ->addActionLabel(__('admin.spots.add-url'))
                    ->simple(
                        Textarea::make('url'),
                    ),
                Hidden::make('user_id')
                    ->formatStateUsing(static fn () => Filament::auth()->id())
                    ->dehydrated(static fn (string $context): bool => $context === 'create'),
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
                TextEntry::make('techniques.name')
                    ->label('Techniques')
                    ->badge(),
                TextEntry::make('categories.name')
                    ->label('Categories')
                    ->badge(),
                TextEntry::make('environmental_factors.name')
                    ->label('Environmental factors')
                    ->badge(),
                ImageEntry::make('images')
                    ->disk('public')
                    ->url(static function (?string $state): ?string {
                        if ($state === null) {
                            return null;
                        }

                        return Storage::disk('public')->url($state);
                    }, true)
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
                TextEntry::make('user.name')
                    ->label('Created by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('distance')
                    ->numeric()
                    ->sortable()
                    ->suffix(' km'),
                TextColumn::make('techniques.name')
                    ->badge(),
                TextColumn::make('categories.name')
                    ->badge(),
                TextColumn::make('environmentalFactors.name')
                    ->badge(),
                ImageColumn::make('images')
                    ->disk('public')
                    ->imageSize(100)
                    ->url(static function (?string $state): ?string {
                        if ($state === null) {
                            return null;
                        }

                        return Storage::disk('public')->url($state);
                    }, true),
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
                TextColumn::make('user.name')
                    ->label('Created by')
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
                    ->label('Techniques')
                    ->relationship('techniques', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category_ids')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                SelectFilter::make('environmental_factor_ids')
                    ->label('Environmental factors')
                    ->relationship('environmentalFactors', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                TrashedFilter::make()
                    ->visible(static fn () => in_array(Filament::auth()->user()->role, [Role::ContentManager, Role::Admin])),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->iconButton(),
                ActionGroup::make([
                    CreateTemporaryUrlAction::make(),
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
            ->with(['techniques:id,name', 'categories:id,name', 'environmentalFactors:id,name', 'user:id,name'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
