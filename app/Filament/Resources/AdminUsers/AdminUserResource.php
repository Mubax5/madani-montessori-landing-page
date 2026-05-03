<?php

namespace App\Filament\Resources\AdminUsers;

use App\Filament\Resources\AdminUsers\Pages\ManageAdminUsers;
use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Support\ImageUpload;
use App\Models\AdminUser;
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
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            TextInput::make('password_hash')
                ->label('Password')
                ->password()
                ->revealable()
                ->required(fn (?AdminUser $record): bool => $record === null)
                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn (?string $state): bool => filled($state)),
            ImageUpload::make('avatar_upload', 'admin-avatars', 'Upload avatar')
                ->helperText('File yang didukung jpeg, png, webp. Max 10MB'),
            TextInput::make('avatar_url')
                ->label('Avatar URL')
                ->url()
                ->rules(['nullable', 'url', 'starts_with:http://,https://'])
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
                ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->getStateUsing(fn (AdminUser $record): ?string => static::avatarPreviewUrl($record->avatar_path))
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
                    ->mutateRecordDataUsing(fn (array $data): array => static::hydrateAvatarInputs($data))
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

    public static function hydrateAvatarInputs(array $data): array
    {
        $path = (string) ($data['avatar_path'] ?? '');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            $data['avatar_url'] = $path;
        } elseif (filled($path)) {
            $data['avatar_upload'] = $path;
        }

        return $data;
    }

    public static function resolveAvatarInputs(array $data): array
    {
        $url = trim((string) ($data['avatar_url'] ?? ''));
        $upload = static::normalizeUploadState($data['avatar_upload'] ?? null);

        $data['avatar_path'] = filled($url) ? $url : $upload;

        unset($data['avatar_upload'], $data['avatar_url']);

        return $data;
    }

    public static function avatarPreviewUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $disk = Storage::disk(config('filesystems.default', 'public'));

        return $disk instanceof FilesystemAdapter ? $disk->url($path) : null;
    }

    private static function normalizeUploadState(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = Arr::first($state);
        }

        return filled($state) ? (string) $state : null;
    }
}
