<?php

namespace App\Filament\Resources\OurservicespageResource\Pages;

use App\Filament\Resources\OurservicespageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurservicespage extends EditRecord
{
    protected static string $resource = OurservicespageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return 'Our services page updated successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
