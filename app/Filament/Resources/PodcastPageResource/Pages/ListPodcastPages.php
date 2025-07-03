<?php

namespace App\Filament\Resources\PodcastPageResource\Pages;

use App\Filament\Resources\PodcastPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPodcastPages extends ListRecords
{
    protected static string $resource = PodcastPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
