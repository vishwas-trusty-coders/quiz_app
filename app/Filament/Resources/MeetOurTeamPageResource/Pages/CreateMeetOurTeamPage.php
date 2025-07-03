<?php

namespace App\Filament\Resources\MeetOurTeamPageResource\Pages;

use App\Filament\Resources\MeetOurTeamPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMeetOurTeamPage extends CreateRecord
{
    protected static string $resource = MeetOurTeamPageResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Meet the team page created successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
