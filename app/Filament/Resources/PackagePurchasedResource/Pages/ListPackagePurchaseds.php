<?php

namespace App\Filament\Resources\PackagePurchasedResource\Pages;

use App\Filament\Resources\PackagePurchasedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackagePurchaseds extends ListRecords
{
    protected static string $resource = PackagePurchasedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
