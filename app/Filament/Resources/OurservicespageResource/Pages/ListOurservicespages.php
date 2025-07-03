<?php

namespace App\Filament\Resources\OurservicespageResource\Pages;

use App\Filament\Resources\OurservicespageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOurservicespages extends ListRecords
{
    protected static string $resource = OurservicespageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
