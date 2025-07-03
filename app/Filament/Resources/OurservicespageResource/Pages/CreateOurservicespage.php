<?php

namespace App\Filament\Resources\OurservicespageResource\Pages;

use App\Filament\Resources\OurservicespageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOurservicespage extends CreateRecord
{
    protected static string $resource = OurservicespageResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Our services page created successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
