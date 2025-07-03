<?php

namespace App\Filament\Resources\PackagePurchasedResource\Pages;

use App\Filament\Resources\PackagePurchasedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackagePurchased extends EditRecord
{
    protected static string $resource = PackagePurchasedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
