<?php

namespace App\Filament\Resources\ServicesSinglePageResource\Pages;

use App\Filament\Resources\ServicesSinglePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServicesSinglePages extends ListRecords
{
    protected static string $resource = ServicesSinglePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
