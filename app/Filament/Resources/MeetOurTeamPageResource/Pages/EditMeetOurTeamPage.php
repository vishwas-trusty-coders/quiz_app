<?php

namespace App\Filament\Resources\MeetOurTeamPageResource\Pages;

use App\Filament\Resources\MeetOurTeamPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeetOurTeamPage extends EditRecord
{
    protected static string $resource = MeetOurTeamPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return 'Meet the team page updated successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
