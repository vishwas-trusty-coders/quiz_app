<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicesSinglePageResource\Pages;
use App\Filament\Resources\ServicesSinglePageResource\RelationManagers;
use App\Models\ServicesSinglePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServicesSinglePageResource extends Resource
{
    protected static ?string $model = ServicesSinglePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Pages'; 

    protected static ?string $navigationLabel = 'Services Single Pages';

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
                    Forms\Components\Textarea::make('section1_description')->label('Section1 Description'),
                    Forms\Components\TextInput::make('section1_title')->label('Section1 Title'),
                    Forms\Components\Repeater::make('quick_facts')
                        ->label('Quick Facts')
                        ->schema([
                            Forms\Components\Textarea::make('description')->label('Description')                    
                        ])
                        ->collapsible(),
                    Forms\Components\TextInput::make('section1_cta_button_txt')->label('Section1 Button Text'),  
                    Forms\Components\TextInput::make('section1_cta_button_url')->label('Section1 Button URL'), 
                    Forms\Components\FileUpload::make('section1_video')->label('Section1 Video')->directory('our_services')
                    ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mkv']) 
                    ->nullable(),
                ])->label('First Section')->collapsible(),

                //section 2
                Forms\Components\Card::make([

                    Forms\Components\Repeater::make('section2_content')
                        ->label('Section 2')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'), 
                            Forms\Components\Repeater::make('sub_content')
                            ->label('Add details')
                            ->schema([
                                Forms\Components\TextInput::make('name')->label('Name'), 
                                Forms\Components\Textarea::make('description')->label('Description')                    
                            ])
                            ->collapsible(),            
                        ])
                        ->collapsible(),

                ])->label('Second Section')->collapsible(),

                // third section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('section3_title')->label('Section3 Title'),
                    Forms\Components\Repeater::make('consultant_services')
                        ->label('Consultant Services')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'), 
                            Forms\Components\Textarea::make('description')->label('Description'),
                            Forms\Components\TextInput::make('read_more_button_txt')->label('Read More Button Text'),  
                            Forms\Components\TextInput::make('read_morebutton_url')->label('Read more Button URL'),  
                            Forms\Components\Repeater::make('services_detail')
                            ->label('Services Detail')
                            ->schema([
                                Forms\Components\TextInput::make('detail')->label('Detail'),            
                            ])
                            ->collapsible(),
                            Forms\Components\TextInput::make('button_txt')->label('Button Text'),  
                            Forms\Components\TextInput::make('button_url')->label('Button URL'),                 
                        ])
                        ->collapsible(),
                ])->label('Third section')->collapsible(),

                // Fourth section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('section4_title')->label('Section4 Title'),
                    Forms\Components\Textarea::make('section4_description')->label('Section4 Description'),
                    Forms\Components\Repeater::make('available_plans')
                        ->label('Available Plans')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'), 
                            Forms\Components\Textarea::make('description')->label('Description'),      
                            Forms\Components\TextInput::make('button_txt')->label('Button Text'),  
                            Forms\Components\TextInput::make('button_url')->label('Button URL'),               
                        ])
                        ->collapsible(),
                ])->label('Fourth section')->collapsible(),
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
            'index' => Pages\ListServicesSinglePages::route('/'),
            'create' => Pages\CreateServicesSinglePage::route('/create'),
            'edit' => Pages\EditServicesSinglePage::route('/{record}/edit'),
        ];
    }
}
