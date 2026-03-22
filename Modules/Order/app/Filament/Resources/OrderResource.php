<?php

declare(strict_types=1);

namespace Modules\Order\Filament\Resources;

use App\Dictionaries\NavigationGroup;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Filament\Resources\OrderResource\Pages\EditOrder;
use Modules\Order\Filament\Resources\OrderResource\Pages\ListOrders;
use Modules\Order\Filament\Resources\OrderResource\Pages\ViewOrder;
use Modules\Order\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use Modules\Order\Models\Order;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static UnitEnum|string|null $navigationGroup = NavigationGroup::ORDERS;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->options(collect(OrderStatus::cases())->mapWithKeys(
                    fn (OrderStatus $status) => [$status->value => ucfirst($status->value)],
                ))
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_email')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->prefix('$')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrderStatus $state) => match ($state) {
                        OrderStatus::PENDING => 'warning',
                        OrderStatus::CONFIRMED => 'info',
                        OrderStatus::SHIPPED => 'primary',
                        OrderStatus::DELIVERED => 'success',
                    })
                    ->formatStateUsing(fn (OrderStatus $state) => ucfirst($state->value))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(
                        fn (OrderStatus $status) => [$status->value => ucfirst($status->value)],
                    )),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
