<?php

namespace App\Filament\Resources\HeaderSettingResource\Pages;

use App\Filament\Resources\HeaderSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeaderSettings extends ListRecords
{
    protected static string $resource = HeaderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
