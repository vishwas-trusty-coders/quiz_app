<?php

namespace App\Filament\Resources\MeetOurTeamPageResource\Pages;

use App\Filament\Resources\MeetOurTeamPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetOurTeamPages extends ListRecords
{
    protected static string $resource = MeetOurTeamPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
