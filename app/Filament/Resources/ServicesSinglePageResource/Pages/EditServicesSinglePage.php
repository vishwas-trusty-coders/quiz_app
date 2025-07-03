<?php

namespace App\Filament\Resources\ServicesSinglePageResource\Pages;

use App\Filament\Resources\ServicesSinglePageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServicesSinglePage extends EditRecord
{
    protected static string $resource = ServicesSinglePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return 'Services updated successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
