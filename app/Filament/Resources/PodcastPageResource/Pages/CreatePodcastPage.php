<?php

namespace App\Filament\Resources\PodcastPageResource\Pages;

use App\Filament\Resources\PodcastPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePodcastPage extends CreateRecord
{
    protected static string $resource = PodcastPageResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Podcast page created successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
