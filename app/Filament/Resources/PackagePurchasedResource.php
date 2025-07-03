<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackagePurchasedResource\Pages;
use App\Models\packagePurchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter; // Add this to use SelectFilter

class PackagePurchasedResource extends Resource
{
    protected static ?string $model = packagePurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 10;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Purchased Package Details')
                ->schema([
                    Infolists\Components\TextEntry::make('user.first_name')
                        ->label('User Name')
                        ->getStateUsing(function ($record) {
                            return $record->user ? $record->user->first_name . ' ' . $record->user->last_name : 'N/A';
                        })
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('question_bank_name')->label('Package Name')->columnSpanFull(),
                    Infolists\Components\TextEntry::make('credits_spent')
                        ->label('Credits')
                        ->columnSpanFull(),
                    // Display subject names as comma separated
                    Infolists\Components\TextEntry::make('subject_name')
                        ->label('Subjects')
                        ->getStateUsing(function ($record) {
                            // Get subject names from related IDs
                            $subjectIds = json_decode($record->subject); // Decode the stored array of IDs
                            $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->pluck('name')->toArray();
                            return implode(', ', $subjects); // Join subject names with commas
                        })
                        ->columnSpanFull(),
                    // Display topic names as comma separated
                    Infolists\Components\TextEntry::make('topic')
                        ->label('Topics')
                        ->getStateUsing(function ($record) {
                            // Get subject names from related IDs
                            $topicIds = json_decode($record->topic); // Decode the stored array of IDs
                            $topics = \App\Models\Topic::whereIn('id', $topicIds)->pluck('name')->toArray();
                            return implode(', ', $topics); // Join subject names with commas
                        })
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_bank_name')->label('Package Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('credits_spent')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created At'),
            ])
            ->filters([
                // Adding Select Filter for question_bank_name
                SelectFilter::make('question_bank_name')
                    ->label('Package Name') // Label for the filter
                    ->options(function () {
                        return packagePurchase::distinct()->pluck('question_bank_name', 'question_bank_name')->toArray();
                    })
                    ->placeholder('Select Package Name'), // Placeholder text
                SelectFilter::make('user_id')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackagePurchaseds::route('/'),
            'view' => Pages\ViewPackagePurchased::route('/{record}')
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
