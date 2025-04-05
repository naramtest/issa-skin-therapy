<?php

namespace App\Filament\Resources\AffiliateResource\Pages;

use App\Filament\Resources\AffiliateResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateAffiliate extends CreateRecord
{
    protected static string $resource = AffiliateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["slug"] = \Str::slug($this->data["user"]["name"]);
        $data["is_admin"] = true;
        $data["email_verified_at"] = now();
        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function afterCreate(): void
    {
        /** @var User $user */
        $user = $this->record->user;
        $role = Role::firstOrCreate(["name" => "affiliate"]);
        $user->assignRole($role);
    }
}
