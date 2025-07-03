<?php

namespace App\Filament\Resources\HeaderSettingResource\Pages;

use App\Filament\Resources\HeaderSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHeaderSetting extends CreateRecord
{
    protected static string $resource = HeaderSettingResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'Header setting saved successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
