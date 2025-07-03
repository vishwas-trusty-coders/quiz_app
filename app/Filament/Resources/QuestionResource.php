<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use App\Models\Subject; 
use App\Models\Topic;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Daothanh\Tinymce\Forms\Components\TinymceField;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('question_label')
                //     ->label('Question Title')
                //     ->required()
                //     ->columnSpan(2),
                TinymceField::make('question') 
                    ->profile('default')
                    ->label('Question')
                    ->id('customid1')
                    ->required()
                    ->columnSpan(2),

                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->options(Subject::all()->pluck('name', 'id'))
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('topic_id')
                    ->label('Topic')
                    ->options(function (callable $get) {
                        $subjectId = $get('subject_id');
                        return $subjectId
                            ? Topic::where('subject_id', $subjectId)->pluck('name', 'id')
                            : [];
                    })
                    ->required(),
                Forms\Components\Select::make('tag_ids')
                    ->multiple()
                    ->options(function () {
                        return \App\Models\Tag::all()->pluck('name', 'id');
                    })
                    ->label('Tags')
                    ->columnSpan(2),

                Forms\Components\Repeater::make('options')
                ->label('Answer Choices')
                ->schema([
                    Forms\Components\TextInput::make('option')
                        ->label('Option')
                        ->required()
                        ->reactive(), // Ensure the field is reactive to changes
                    
                ])
                ->columnSpan(2)
                ->defaultItems(5)
                ->createItemButtonLabel('Add Answer Choice')
                ->disableItemMovement(),
            
                // Add the Correct Answer field outside the repeater
                Forms\Components\Select::make('correct_answer')
                    ->label('Correct Answer')
                    ->options(function (callable $get) {
                        // Get the number of options in the repeater
                        $optionsCount = count($get('options'));
            
                        // Dynamically generate options based on the repeater count
                        $letters = range('A', 'Z'); // To support up to 26 options
            
                        $correctAnswerOptions = [];
                        for ($i = 0; $i < $optionsCount; $i++) {
                            // Associate each option with a letter
                            $correctAnswerOptions[$letters[$i]] = $letters[$i];
                        }
            
                        return $correctAnswerOptions;
                    })
                    ->required()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // This will trigger when the correct_answer field is updated
                        $set('correct_answer', $state);
                    })
                    ->columnSpan(2),

                TinymceField::make('explanation')
                    ->profile('default')
                    ->label('Explanation')
                    ->id('customid2')
                    ->nullable()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->sortable(),
                TextColumn::make('tag_ids')
                    ->label('Tags')
                    ->formatStateUsing(function ($state, $record) {
                        return collect($record->tag_names)->join(', ');
                    }),
                TextColumn::make('topic.name')->label('Topic'),
                TextColumn::make('topic.subject.name')->label('Subject'),
                TextColumn::make('correct_answer')->label('Correct Answer')
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
