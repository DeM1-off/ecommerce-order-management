<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Dictionaries\NavigationGroup;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Modules\Catalog\Filament\Resources\CategoryResource\Pages\EditCategory;
use Modules\Catalog\Filament\Resources\CategoryResource\Pages\ListCategories;
use Modules\Catalog\Models\Category;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';
    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::CATALOG;
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
