<?php

namespace App\Filament\Resources\ServicesSinglePageResource\Pages;

use App\Filament\Resources\ServicesSinglePageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServicesSinglePage extends CreateRecord
{
    protected static string $resource = ServicesSinglePageResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Services created successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
