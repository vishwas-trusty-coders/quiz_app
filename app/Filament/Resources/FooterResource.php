<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterResource\Pages;
use App\Models\Footer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FooterResource extends Resource
{
    protected static ?string $model = Footer::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('logo')
                ->label('Logo')
                ->directory('logos')
                ->required(),
            Forms\Components\TextInput::make('tagline')
                ->label('Tagline')
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->required(),

            // Footer Links (as JSON)
            Forms\Components\Repeater::make('links')
                ->label('Footer Links')
                ->schema([
                    Forms\Components\TextInput::make('section')
                        ->label('Section Name')
                        ->default('Quick Links')
                        ->required(),
                    Forms\Components\TextInput::make('label')
                        ->label('Label')
                        ->required(),
                    Forms\Components\TextInput::make('url')
                        ->label('URL'),
                ])
                ->columns(3)
                ->collapsed(),

            // Social Links (as JSON)
            Forms\Components\Repeater::make('social_links')
                ->label('Social Media Links')
                ->schema([
                    Forms\Components\TextInput::make('platform')
                        ->label('Platform')
                        ->required(),
                    Forms\Components\TextInput::make('icon')
                        ->label('Icon Class (e.g., fa-facebook)')
                        ->required(),
                    Forms\Components\TextInput::make('url')
                        ->label('URL')
                        ->required(),
                ])
                ->columns(3)
                ->collapsed(),

            // Copyright, Privacy Policy, Terms of Use
            Forms\Components\TextInput::make('copyright')
                ->label('Copyright Text')
                ->required(),
            Forms\Components\Repeater::make('custom_links')
                ->label('Footer (copyright) section links')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required(),
                    Forms\Components\TextInput::make('url')
                        ->label('URL')
                        ->required(),
                ])
                ->columns(2) // Arrange inputs side by side
                ->collapsed(), // Keep it collapsed by default
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tagline')->label('Tagline')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email'),

                // Dynamic count of links
                Tables\Columns\TextColumn::make('links_count')
                    ->label('Number of Links')
                    ->getStateUsing(fn (Footer $record) => is_array($record->links) ? count($record->links) : 0),

                // Dynamic count of social links
                Tables\Columns\TextColumn::make('social_links_count')
                    ->label('Number of Social Links')
                    ->getStateUsing(fn (Footer $record) => is_array($record->social_links) ? count($record->social_links) : 0),
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
            'index' => Pages\ListFooters::route('/'),
            'create' => Pages\CreateFooter::route('/create'),
            'edit' => Pages\EditFooter::route('/{record}/edit'),
        ];
    }
}
