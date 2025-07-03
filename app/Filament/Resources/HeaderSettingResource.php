<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeaderSettingResource\Pages;
use App\Filament\Resources\HeaderSettingResource\RelationManagers;
use App\Models\HeaderSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HeaderSettingResource extends Resource
{
    protected static ?string $model = HeaderSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('logo')
                ->label('Logo')
                ->image()
                ->directory('logos')
                ->nullable()
                ->helperText('Upload your logo here'),

            Forms\Components\Repeater::make('menu_items')
                ->label('Menu Items')
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('Menu Item Label')
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->label('Link Type')
                        ->options([
                            'custom' => 'Custom Link',
                            // 'page' => 'Page Link',
                        ])
                        ->default('custom')
                        ->required(),

                    // Conditional fields based on link type
                    // Forms\Components\Select::make('page_link')
                    //     ->label('Page Link')
                    //     ->options(function () {
                    //         // Get all pages from the system (could be dynamic)
                    //         return \App\Models\Page::pluck('title', 'slug');
                    //     })
                    //     ->required()
                    //     ->hidden(fn ($get) => $get('type') !== 'page'),

                    Forms\Components\TextInput::make('custom_link')
                        ->label('Custom Link')
                        ->hidden(fn ($get) => $get('type') !== 'custom'),
                ])
                ->columns(1)
                ->defaultItems(1)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('logo')->label('Logo'),
                // Tables\Columns\TextColumn::make('menu_items')->label('Menu Items')->limit(50),
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
            'index' => Pages\ListHeaderSettings::route('/'),
            'create' => Pages\CreateHeaderSetting::route('/create'),
            'edit' => Pages\EditHeaderSetting::route('/{record}/edit'),
        ];
    }
}
