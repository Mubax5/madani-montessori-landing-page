<?php

namespace App\Filament\Resources\AdminUsers;

use App\Filament\Resources\AdminUsers\Pages\ManageAdminUsers;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Support\ImageUpload;
use App\Models\AdminUser;
use App\Rules\AllowedExternalUrl;
use App\Support\MediaUrl;
use App\Support\Security\AdminPassword;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminUserResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = AdminUser::class;

    protected static string $permissionScope = 'admin';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Admin User';

    protected static ?string $modelLabel = 'Admin';

    protected static ?string $pluralModelLabel = 'Admin User';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('role_id')
                ->label('Role')
                ->relationship('role', 'name')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(120),
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(150),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->revealable()
                ->required(fn (?AdminUser $record): bool => $record === null)
                ->rules(AdminPassword::rules())
                ->maxLength(72)
                ->autocomplete('new-password')
                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn (?string $state): bool => filled($state)),
            TextInput::make('password_confirmation')
                ->label('Konfirmasi password')
                ->password()
                ->revealable()
                ->required(fn (?AdminUser $record): bool => $record === null)
                ->same('password')
                ->maxLength(72)
                ->autocomplete('new-password')
                ->dehydrated(false)
                ->visible(fn (?AdminUser $record, $get): bool => $record === null || filled($get('password'))),
            ImageUpload::make('avatar_path', 'avatars', 'Upload avatar')
                ->helperText('File yang didukung jpeg, png, webp. Max 10MB'),
            TextInput::make('avatar_url')
                ->label('Avatar URL')
                ->url()
                ->rules(['nullable', new AllowedExternalUrl])
                ->maxLength(2048)
                ->helperText('Opsional. Jika diisi, URL ini akan dipakai dan mengabaikan upload avatar.'),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('avatar_final_url')
                    ->label('Avatar')
                    ->getStateUsing(fn (AdminUser $record): ?string => $record->avatar_final_url)
                    ->defaultImageUrl(MediaUrl::placeholderDataUri('Avatar'))
                    ->imageSize(42)
                    ->circular(),
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role.name')->label('Role')->badge(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('last_login_at')->label('Login terakhir')->dateTime('d M Y H:i')->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateDataUsing(fn (array $data): array => static::resolveAvatarInputs($data)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAdminUsers::route('/'),
        ];
    }

    public static function resolveAvatarInputs(array $data): array
    {
        if (filled($data['password'] ?? null)) {
            $data['password_hash'] = $data['password'];
        }

        unset($data['password'], $data['password_confirmation']);

        $data['avatar_url'] = MediaUrl::normalizeManualUrl($data['avatar_url'] ?? null);
        $data['avatar_path'] = MediaUrl::normalizePath($data['avatar_path'] ?? null);

        if (MediaUrl::isTemporaryPath($data['avatar_path'])) {
            throw ValidationException::withMessages([
                'avatar_path' => 'Upload avatar belum tersimpan ke folder final. Upload ulang avatar.',
            ]);
        }

        return $data;
    }
}
