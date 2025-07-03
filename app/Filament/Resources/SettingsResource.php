<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages;
use App\Models\Settings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingsResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('admin_email')
                    ->label('Admin Email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('support_email')
                    ->label('Support Email')
                    ->email()
                    ->required(),
            
                  // Add PayPal Environment Selection
                Forms\Components\Select::make('paypal_environment')
                    ->label('PayPal Environment')
                    ->options([
                        'sandbox' => 'Sandbox',
                        'live' => 'Live',
                    ])
                    ->default('sandbox')->columnSpan(2),

                  // Add PayPal Credentials
                Forms\Components\TextInput::make('paypal_sandbox_client_id')
                    ->label('PayPal Sandbox Client ID'),

                Forms\Components\TextInput::make('paypal_sandbox_client_secret')
                    ->label('PayPal Sandbox Client Secret'),

                Forms\Components\TextInput::make('paypal_production_client_id')
                    ->label('PayPal Production Client ID'),

                Forms\Components\TextInput::make('paypal_production_client_secret')
                    ->label('PayPal Production Client Secret'),

                Forms\Components\TextInput::make('currency')
                    ->label('Currency'),
                Forms\Components\Textarea::make('top_header_text')
                    ->label('Top Header Text')
                    ->rows(2),

                Forms\Components\Repeater::make('social_media_links')
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
                            ->url()
                            ->required(),
                    ])
                    ->columns(3)
                    ->required(),

                

                Forms\Components\Repeater::make('credit_info')
                    ->label('Credit Info')
                    ->schema([
                        Forms\Components\TextInput::make('number_of_credit')
                            ->label('Number of Credits'),

                        Forms\Components\TextInput::make('price_of_credit')
                            ->label('Price'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admin_email')
                    ->label('Admin Email'),

                Tables\Columns\TextColumn::make('support_email')
                    ->label('Support Email'),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),

                Tables\Columns\TextColumn::make('top_header_text')
                    ->label('Top Header Text')
                    ->limit(50),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSettings::route('/create'),
            'edit' => Pages\EditSettings::route('/{record}/edit'),
        ];
    }
}
