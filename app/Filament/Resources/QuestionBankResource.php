<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionBankResource\Pages;
use App\Filament\Resources\QuestionBankResource\RelationManagers;
use App\Models\QuestionBank;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionBankResource extends Resource
{
    protected static ?string $model = QuestionBank::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Question Bank Name'),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->label('Description'),

                Forms\Components\TextInput::make('credit')
                    ->required()
                    ->numeric()
                    ->label('Credit'),

                Forms\Components\Select::make('subjects')
                    
    ->multiple()
    ->options(\App\Models\Subject::all()->pluck('name', 'id')->toArray())
    ->label('Subjects')
    ->afterStateUpdated(function ($state, $set) {
        // Ensure $state is always an array
        $subjectIds = is_array($state) ? $state : [];

        // Protect against empty array (optional)
        if (empty($subjectIds)) {
            $set('topics', []);
            return;
        }

        $topics = \App\Models\Topic::whereIn('subject_id', $subjectIds)->pluck('id')->toArray();

        \Log::info('Selected Subjects:', $subjectIds);
        \Log::info('Associated Topics:', $topics);

        $set('topics', $topics);
    }),
                    

                Forms\Components\Hidden::make('topics'), 
                Forms\Components\Select::make('tags')
                    ->multiple()
                    ->options(function () {
                        return \App\Models\Tag::all()->pluck('name', 'id');
                    })
                    ->label('Tags')
            ]);
    }
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Populate topics based on selected subjects before saving
        $data['topics'] = \App\Models\Topic::whereIn('subject_id', $data['subjects'])->pluck('id')->toArray();
        return $data;
    }
    
    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Same logic for update
        $data['topics'] = \App\Models\Topic::whereIn('subject_id', $data['subjects'])->pluck('id')->toArray();
        return $data;
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                // Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('credit')->label('Credit'),
                Tables\Columns\TextColumn::make('subjects')->label('Subjects')->sortable(),
                Tables\Columns\TextColumn::make('topics')->label('Topics'),
                Tables\Columns\TextColumn::make('tags')->label('Tags'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListQuestionBanks::route('/'),
            'create' => Pages\CreateQuestionBank::route('/create'),
            'edit' => Pages\EditQuestionBank::route('/{record}/edit'),
        ];
    }
}
