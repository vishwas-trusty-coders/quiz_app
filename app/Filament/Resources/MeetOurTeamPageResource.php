<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetOurTeamPageResource\Pages;
use App\Filament\Resources\MeetOurTeamPageResource\RelationManagers;
use App\Models\MeetOurTeamPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetOurTeamPageResource extends Resource
{
    protected static ?string $model = MeetOurTeamPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Pages'; 

    protected static ?string $navigationLabel = 'Meet Our Team';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('title')->label('Title')->required(),
                    Forms\Components\Textarea::make('description')->label('Description'),
                ])->label('Page title'),

                // First Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('section1_title')->label('Section1 Title'),
                    
                    Forms\Components\FileUpload::make('leaders1_image')->label('Leaders1 Image') ->directory('team')
                        ->image() 
                        ->nullable(),
                    Forms\Components\TextInput::make('leaders1_name')->label('Leaders1 Name'),
                    Forms\Components\TextInput::make('leaders1_designation')->label('Leaders1 Designation'),
                    Forms\Components\Textarea::make('leaders1_description')->label('Leaders1 Description'),
                    Forms\Components\TextInput::make('exam_score_title')->label('Exam Info Title'),
                    Forms\Components\Repeater::make('exam_score_info')
                        ->label('Exam Scores info')
                        ->schema([
                            Forms\Components\TextInput::make('detail')->label('Add Detail')
                        ]),
                    Forms\Components\TextInput::make('credential_title')->label('Credential Title'),
                    Forms\Components\Repeater::make('credential_info')
                        ->label('Credential info')
                        ->schema([
                            Forms\Components\TextInput::make('detail1')->label('Main Detail'),
                            Forms\Components\TextInput::make('detail2')->label('Sub Detail')
                        ]),
                    Forms\Components\FileUpload::make('leaders2_image')->label('Leaders2 Image') ->directory('team')
                        ->image() 
                        ->nullable(),
                    Forms\Components\TextInput::make('leaders2_name')->label('Leaders2 Name'),
                    Forms\Components\TextInput::make('leaders2_designation')->label('Leaders2 Designation'),
                    Forms\Components\Textarea::make('leaders2_description')->label('Leaders2 Description'),       
                        
                ])->label('First Section')->collapsible(),

                // Second Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('section2_title')->label('Section2 Title'),
                    Forms\Components\TextArea::make('section2_desc')->label('Section2 Description'),
                    Forms\Components\Repeater::make('tutor_detail')
                        ->label('Tutor Detail')
                        ->schema([
                            Forms\Components\FileUpload::make('tutors_image')->label('Tutors Image') ->directory('team')
                                ->image() 
                                ->nullable(),
                            Forms\Components\TextInput::make('tutors_name')->label('Tutors Name'),
                            Forms\Components\TextInput::make('tutors_designation')->label('Tutors Designation'),
                            Forms\Components\TextInput::make('cta_btn_text')->label('Button Text'),
                            Forms\Components\TextInput::make('cta_btn_url')->label('Button URL'),
                        ])
                ])->label('Second Section')->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make('description')->label('Description')->limit(50),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Updated'),
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
            'index' => Pages\ListMeetOurTeamPages::route('/'),
            'create' => Pages\CreateMeetOurTeamPage::route('/create'),
            'edit' => Pages\EditMeetOurTeamPage::route('/{record}/edit'),
        ];
    }
}
