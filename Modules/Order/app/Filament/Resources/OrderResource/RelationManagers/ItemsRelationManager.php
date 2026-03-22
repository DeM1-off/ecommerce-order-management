<?php

declare(strict_types=1);

namespace Modules\Order\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('price')
                    ->prefix('$'),
                TextColumn::make('quantity'),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->prefix('$')
                    ->state(fn ($record) => number_format((float) $record->price * $record->quantity, 2)),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
