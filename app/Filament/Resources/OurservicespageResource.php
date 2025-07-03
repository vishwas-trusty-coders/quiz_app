<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OurservicespageResource\Pages;
use App\Filament\Resources\OurservicespageResource\RelationManagers;
use App\Models\Ourservicespage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OurservicespageResource extends Resource
{
    protected static ?string $model = Ourservicespage::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Pages'; 

    protected static ?string $navigationLabel = 'Our Services';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('title')->label('Title')->required(),
                    Forms\Components\Textarea::make('description')->label('Description'),
                ])->label('Page title'),

                // Study Buddy Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('service1_title')->label('Service1 Title')->required(),
                    Forms\Components\Textarea::make('service1_description')->label('Service1 Description'),
                    Forms\Components\TextInput::make('service1_title2')->label('Service Title2'),
                    Forms\Components\Repeater::make('service1_how_it_work')
                        ->label('How it works')
                        ->schema([
                            Forms\Components\TextInput::make('name')->label('Name'), 
                            Forms\Components\Textarea::make('description')->label('Description')                    
                        ])
                        ->collapsible(),
                    Forms\Components\Repeater::make('cta_button_service1')
                        ->label('CTA Buttons')
                        ->schema([
                            Forms\Components\TextInput::make('btn_txt')->label('Button Text'),  
                            Forms\Components\TextInput::make('btn_url')->label('Button URL'),  
                        ])
                        ->collapsible(),
                    Forms\Components\FileUpload::make('service1_image')->label('Service1 Image')->directory('our_services')
                    ->image() 
                    ->nullable(),
                    Forms\Components\Textarea::make('service1_more_description')->label('Service1 More Description'),
                ])->label('Study Buddy Section')->collapsible(),

                // Study Buddy Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('service2_title')->label('Service2 Title')->required(),
                    Forms\Components\Textarea::make('service2_description')->label('Service2 Description'),
                    Forms\Components\TextInput::make('service2_title2')->label('Service Title2'),
                    Forms\Components\Repeater::make('service2_how_it_work')
                        ->label('How it works')
                        ->schema([
                            Forms\Components\TextInput::make('name')->label('Name'), 
                            Forms\Components\Textarea::make('description')->label('Description')                    
                        ])
                        ->collapsible(),
                    Forms\Components\Repeater::make('cta_button_service2')
                        ->label('CTA Buttons')
                        ->schema([
                            Forms\Components\TextInput::make('btn_txt')->label('Button Text'),  
                            Forms\Components\TextInput::make('btn_url')->label('Button URL'),  
                        ])
                        ->collapsible(),
                    Forms\Components\FileUpload::make('service2_image')->label('Service2 Image')->directory('our_services')
                    ->image() 
                    ->nullable(),
                    Forms\Components\Textarea::make('service2_more_description')->label('Service2 More Description'),
                ])->label('Tutor Arcade Section')->collapsible(),
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
            'index' => Pages\ListOurservicespages::route('/'),
            'create' => Pages\CreateOurservicespage::route('/create'),
            'edit' => Pages\EditOurservicespage::route('/{record}/edit'),
        ];
    }
}
