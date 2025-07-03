<?php

namespace App\Filament\Resources\FooterResource\Pages;

use App\Filament\Resources\FooterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFooter extends CreateRecord
{
    protected static string $resource = FooterResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Footer setting saved successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
