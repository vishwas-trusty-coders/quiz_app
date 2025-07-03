<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 10;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Details')
                ->schema([
                    Infolists\Components\TextEntry::make('user.first_name')
                    ->label('User Name')
                    ->getStateUsing(function ($record) {
                        return $record->user ? $record->user->first_name . ' ' . $record->user->last_name : 'N/A';
                    })
                    ->columnSpanFull(), // Make this field take up full width
                Infolists\Components\TextEntry::make('amount')
                    ->label('Amount')
                    ->getStateUsing(function ($record) {
                        return '$' . number_format($record->amount, 2);
                    })->columnSpanFull(), 
                Infolists\Components\TextEntry::make('paypal_order_id')->columnSpanFull(), 
                Infolists\Components\TextEntry::make('credits')->columnSpanFull(), 
                Infolists\Components\TextEntry::make('status')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('Created At')->columnSpanFull(), 
                ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User Name')
                    ->getStateUsing(function ($record) {
                        return $record->user ? $record->user->first_name . ' ' . $record->user->last_name : 'N/A';
                    })
                    ->sortable(), // Adding searchable functionality to User Name
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->money()
                    ->getStateUsing(function ($record) {
                        return '$' . number_format($record->amount, 2);
                    }),
                Tables\Columns\TextColumn::make('credits')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created At'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ])
                    ->label('Status Filter'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->options(function () {
                        // Fetch users as an associative array of [id => full_name]
                        return \App\Models\User::selectRaw("id, CONCAT(first_name, ' ', last_name) as name")
                            ->pluck('name', 'id');
                }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}')
        ];
    }
    public static function canCreate(): bool
    {
       return false;
    }
}
