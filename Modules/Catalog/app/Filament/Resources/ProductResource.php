<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Dictionaries\NavigationGroup;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\CreateProduct;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\EditProduct;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\ListProducts;
use Modules\Catalog\Models\Product;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::CATALOG;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->nullable()
                ->rows(3),
            TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('$')
                ->step(0.01)
                ->minValue(0),
            TextInput::make('stock')
                ->required()
                ->integer()
                ->minValue(0),
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
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('price')
                    ->prefix('$')
                    ->sortable(),
                TextColumn::make('stock')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
