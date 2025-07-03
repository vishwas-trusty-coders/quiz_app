<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageResource\Pages;
use App\Filament\Resources\HomepageResource\RelationManagers;
use App\Models\Homepage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomepageResource extends Resource
{
    protected static ?string $model = Homepage::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Pages'; 

    protected static ?string $navigationLabel = 'Homepage';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    // Hero Section
                    Forms\Components\TextInput::make('hero_title')->label('Hero Title')->required(),
                    Forms\Components\TextInput::make('hero_subtitle')->label('Hero Subtitle'),
                    Forms\Components\Textarea::make('hero_description')->label('Hero Description'),
                    // Forms\Components\FileUpload::make('hero_image')->label('Hero Image') ->directory('homepage')
                    // ->image() 
                    // ->nullable(),
                    // Forms\Components\FileUpload::make('hero_bg_image')->label('Hero Background Image') ->directory('homepage')
                    // ->image() 
                    // ->nullable(),
                    Forms\Components\TextInput::make('cta_text1')->label('CTA Button1 Text'),
                    Forms\Components\TextInput::make('cta_link1')->label('CTA Button1 Link'),
                    Forms\Components\TextInput::make('cta_text2')->label('CTA Button2 Text'),
                    Forms\Components\TextInput::make('cta_link2')->label('CTA Button2 Link'),
    
                    // Hero Statistics
                    Forms\Components\Repeater::make('statistics')
                        ->label('Hero Section Statistics')
                        ->schema([
                            Forms\Components\FileUpload::make('icon_image')->label('Icon Image') ->directory('homepage')
                            ->image() 
                            ->nullable(),
                            Forms\Components\TextInput::make('number')->label('Number'),
                            Forms\Components\TextInput::make('label')->label('Label'),
                        ])
                        ->collapsible(),
                ])->label('Hero Section'),
                // About Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('about_title')->label('About Title')->required(),
                    Forms\Components\Textarea::make('about_description')->label('About Description'),
                    Forms\Components\Repeater::make('mission_and_values')
                        ->label('Mission and Values')
                        ->schema([
                            Forms\Components\Textarea::make('values_of_mission')                            
                        ])
                        ->collapsible(),
                    Forms\Components\Repeater::make('founder_data')
                        ->label('Founder Data')
                        ->schema([
                            Forms\Components\TextInput::make('founder_name')->label('Founder Name'),  
                            Forms\Components\TextInput::make('founder_designation')->label('Founder Designation'),   
                            Forms\Components\FileUpload::make('founder_image')->label('Founder Image') ->directory('homepage')
                            ->image() 
                            ->nullable(),                      
                        ])
                        ->collapsible(),
                    Forms\Components\Repeater::make('educational_approach')
                        ->label('​Educational Approach')
                        ->schema([
                            Forms\Components\Textarea::make('education_approach')                            
                        ])
                        ->collapsible(),
                    Forms\Components\TextInput::make('about_us_button_text')->label('About us button text'),
                    Forms\Components\TextInput::make('about_us_button_url')->label('About us button URL'),
                    Forms\Components\FileUpload::make('about_image')->label('About Us Image') ->directory('homepage')
                    ->image() 
                    ->nullable(),
                    Forms\Components\FileUpload::make('about_video')->label('About Us Video') ->directory('homepage')
                    ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mkv']) 
                    ->rules('max:51200')
                    ->nullable(),
                ])->label('About Section'),

                // why choose us Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('why_title')->label('Why Choose Us Title')->required(),
                    Forms\Components\TextInput::make('why_subtitle')->label('Why Choose Us Subtitle'),
                    Forms\Components\Repeater::make('why_choose_us_data')
                        ->label('Why Choose Us Data')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'),  
                            Forms\Components\TextArea::make('description')->label('Description'),
                        ])
                        ->collapsible(),
                    Forms\Components\FileUpload::make('why_choose_us_video')->label('Why Choose Us Video') ->directory('homepage')
                        ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mkv']) 
                        ->rules('max:51200')
                        ->nullable(),
                ])->label('Why Choose Us Section'),

                // Testimonial Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('testimonial_title')->label('Testimonial Title')->required(),
                    Forms\Components\TextInput::make('testimonial_subtitle')->label('Testimonial Subtitle'),
                    Forms\Components\Repeater::make('testimonial_data')
                        ->label('Testimonial Data')
                        ->schema([
                            Forms\Components\TextInput::make('star')->label('Rating'),  
                            Forms\Components\TextArea::make('description')->label('Description'),
                            Forms\Components\TextInput::make('name')->label('Name'),
                            Forms\Components\FileUpload::make('image')->label('Image') ->directory('homepage')
                                ->image() 
                                ->nullable(),
                        ])
                        ->collapsible(),
                ])->label('Testimonial Section'),

                // Tutor Expert Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('tutor_title')->label('Tutor Title')->required(),
                    Forms\Components\TextInput::make('tutor_subtitle')->label('Tutor Subtitle'),
                    Forms\Components\TextArea::make('tutor_description')->label('Tutor Description'),
                    Forms\Components\TextInput::make('tutor_button_text')->label('Tutor Button Text'),
                    Forms\Components\TextInput::make('tutor_button_link')->label('Tutor Button Link'),
                    Forms\Components\Repeater::make('tutor_image')
                        ->label('Tutor Image')
                        ->schema([
                            Forms\Components\TextArea::make('description')->label('Description'),
                            Forms\Components\TextInput::make('name')->label('Name'),
                            Forms\Components\FileUpload::make('image')->label('Image') ->directory('homepage')
                                ->image() 
                                ->nullable(),
                        ])
                        ->collapsible(),
                ])->label('Tutor Expert Section'),

                // Podcast Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('podcast_title')->label('Podcast Title')->required(),
                    Forms\Components\TextInput::make('podcast_subtitle')->label('Podcast Subtitle'),
                    Forms\Components\TextArea::make('podcast_description')->label('Podcast Description'),
                    Forms\Components\TextInput::make('podcast_button_text')->label('Podcast Button Text'),
                    Forms\Components\TextInput::make('podcast_button_link')->label('Podcast Button Link'),
                    Forms\Components\Repeater::make('podcast_image')
                        ->label('Podcast Image')
                        ->schema([
                            Forms\Components\TextArea::make('description')->label('Description'),
                            Forms\Components\TextInput::make('name')->label('Name'),
                            Forms\Components\FileUpload::make('image')->label('Image') ->directory('homepage')
                                ->image() 
                                ->nullable(),
                        ])
                        ->collapsible(),
                ])->label('Podcast Section'),

                // FAQ Section
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('faq_title')->label('FAQ Title')->required(),
                    Forms\Components\TextInput::make('faq_subtitle')->label('FAQ Subtitle'),
                    Forms\Components\FileUpload::make('faq_image')->label('faq_Image') ->directory('homepage')
                                ->image() 
                                ->nullable(),
                    Forms\Components\Repeater::make('faq_list')
                        ->label('FAQ Lists')
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('Title'),
                            Forms\Components\TextArea::make('description')->label('Description'),
                        ])
                        ->collapsible(),
                ])->label('FAQ Section'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hero_title')->label('Hero Title'),
                Tables\Columns\TextColumn::make('about_title')->label('About Title'),
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
            'index' => Pages\ListHomepages::route('/'),
            'create' => Pages\CreateHomepage::route('/create'),
            'edit' => Pages\EditHomepage::route('/{record}/edit'),
        ];
    }
}
