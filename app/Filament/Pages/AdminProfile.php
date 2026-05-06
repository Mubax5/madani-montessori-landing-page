<?php

namespace App\Filament\Pages;

use App\Support\Security\AdminPassword;
use Filament\Auth\Pages\EditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminProfile extends EditProfile
{
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/edit-profile.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->rules(AdminPassword::rules())
            ->maxLength(72)
            ->showAllValidationMessages()
            ->autocomplete('new-password')
            ->dehydrated(fn ($state): bool => filled($state))
            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
            ->live(debounce: 500)
            ->same('passwordConfirmation');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (array_key_exists('password', $data)) {
            $data['password_hash'] = $data['password'];
            unset($data['password']);
        }

        $record->update($data);

        return $record;
    }
}
