<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 10;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Quiz Details')
                ->schema([
                    Infolists\Components\TextEntry::make('name')->label('Quiz Name')->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('user.first_name')
                        ->label('User Name')
                        ->getStateUsing(function ($record) {
                            return $record->user ? $record->user->first_name . ' ' . $record->user->last_name : 'N/A';
                        })
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('question_bank_id')->label('Question Bank')
                        ->getStateUsing(function ($record) {
                            return $record->questionBank ? $record->questionBank->name : 'N/A';
                        })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('tutor_mode')->getStateUsing(function ($record) {
                        // Convert status to a more readable format
                        $tutor_mode = $record->tutor_mode;
                        return ucfirst(str_replace('_', ' ', $tutor_mode));
                    })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('timed_mode')->getStateUsing(function ($record) {
                        // Convert status to a more readable format
                        $timed_mode = $record->timed_mode;
                        return ucfirst(str_replace('_', ' ', $timed_mode));
                    })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('subject_ids')
                        ->label('Subjects')
                        ->getStateUsing(function ($record) {
                            // Decode the subject IDs, fetch the names and join them with commas
                            $subjects = $record->subjects()->pluck('name')->toArray();
                            return $subjects ? implode(', ', $subjects) : 'N/A';
                        })
                        ->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('topic_ids')->label('Topics')
                        ->getStateUsing(function ($record) {
                            // Decode the subject IDs, fetch the names and join them with commas
                            $topics = $record->topics()->pluck('name')->toArray();
                            return $topics ? implode(', ', $topics) : 'N/A';
                        })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('question_status') 
                        ->getStateUsing(function ($record) {
                            // Convert status to a more readable format
                            $question_status = $record->question_status;
                            return ucfirst(str_replace('_', ' ', $question_status));
                        })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('question_quantity')->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('score')->label('Score')->getStateUsing(function ($record) {
                        return $record->score . '%';
                    })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('status')->label('Status')
                        ->getStateUsing(function ($record) {
                            // Convert status to a more readable format
                            $status = $record->status;
                            return ucfirst(str_replace('_', ' ', $status));
                        })->columnSpanFull(), 
                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Created At')->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->Label('Quiz Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User Name')
                    ->getStateUsing(function ($record) {
                        return $record->user ? $record->user->first_name . ' ' . $record->user->last_name : 'N/A';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_bank_id')->label('Question Bank')
                    ->getStateUsing(function ($record) {
                        return $record->questionBank ? $record->questionBank->name : 'N/A';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->getStateUsing(function ($record) {
                        // Convert status to a more readable format
                        $status = $record->status;
                        return ucfirst(str_replace('_', ' ', $status));
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created At'),
            ])
            ->filters([
                SelectFilter::make('question_bank_id')
                ->label('Question Bank')
                ->options(function () {
                    // Fetch question banks as an associative array of [id => name]
                    return \App\Models\QuestionBank::pluck('name', 'id');
                }),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'view' => Pages\ViewQuiz::route('/{record}')
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
