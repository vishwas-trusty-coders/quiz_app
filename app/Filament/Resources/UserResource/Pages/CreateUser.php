<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected static bool $canCreateAnother = false;

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'User created successfully!';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index'); // Redirect to the listing page
    }

    protected function afterCreate(): void
    {
        // Get the Administrator role ID
        $adminRoleId = Role::where('name', 'Administrator')->value('id');
        // Update the `is_admin` column based on the role
        $this->record->is_admin = $this->record->role_id == $adminRoleId ? 1 : 0;
        // Save the record after updating the `is_admin` field
        $this->record->save();
    }
}
