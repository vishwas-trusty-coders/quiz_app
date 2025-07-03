<?php

namespace App\Filament\Resources\HeaderSettingResource\Pages;

use App\Filament\Resources\HeaderSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHeaderSetting extends EditRecord
{
    protected static string $resource = HeaderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return 'Header setting updated successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }
}
