<?php

namespace App\Filament\Resources\PodcastPageResource\Pages;

use App\Filament\Resources\PodcastPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPodcastPage extends EditRecord
{
    protected static string $resource = PodcastPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return 'Podcast page updated successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
