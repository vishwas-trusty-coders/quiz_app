<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PodcastPageResource\Pages;
use App\Filament\Resources\PodcastPageResource\RelationManagers;
use App\Models\PodcastPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PodcastPageResource extends Resource
{
    protected static ?string $model = PodcastPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Pages'; 

    protected static ?string $navigationLabel = 'Podcast';

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
                    Forms\Components\TextInput::make('section1_title')->label('Section1 Title')->required(),
                    Forms\Components\TextInput::make('section1_subtitle')->label('Service Sub Title'),
                    Forms\Components\Textarea::make('section1_description')->label('Section1 Description'),
                    Forms\Components\FileUpload::make('section1_image')->label('Section1 Image')->directory('podcast')
                    ->image() 
                    ->nullable(),
                ])->label('First Section')->collapsible(),

                //Second Section
                Forms\Components\Card::make([
                    Forms\Components\Repeater::make('podcast_details')
                        ->label('Podcast Details')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'), 
                            Forms\Components\Textarea::make('description')->label('Description'),
                            Forms\Components\DatePicker::make('date')->label('Date'), 
                            Forms\Components\TextInput::make('duration')->label('Duration'), 
                            Forms\Components\TextInput::make('video_link')->label('Video Link')->url(),   
                            Forms\Components\FileUpload::make('image')->label('Image')->directory('podcast')->image()->nullable(),                  
                        ])->collapsible(),
                ])->label('Second Section')->collapsible(),

                //Third Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('section_title')->label('Section Title'), 
                    Forms\Components\Repeater::make('guest_details')
                        ->label('Guest Speaker Details')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'), 
                            Forms\Components\TextInput::make('video_link')->label('Video Link')->url(),   
                            Forms\Components\FileUpload::make('image')->label('Image')->directory('podcast')->image()->nullable(),                  
                        ])->collapsible(),
                ])->label('Third Section')->collapsible(),
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
            'index' => Pages\ListPodcastPages::route('/'),
            'create' => Pages\CreatePodcastPage::route('/create'),
            'edit' => Pages\EditPodcastPage::route('/{record}/edit'),
        ];
    }
}
