<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\LandingPagePreview;
use App\Models\SiteSetting;
use App\Rules\AllowedExternalUrl;
use App\Rules\InternalOrAllowedExternalUrl;
use App\Support\MediaUrl;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class SiteSettingResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = SiteSetting::class;

    protected static string $permissionScope = 'settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Website';

    protected static ?string $modelLabel = 'Setting';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    protected static ?string $recordTitleAttribute = 'setting_key';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('setting_key')->label('Key')->required()->unique(ignoreRecord: true)->maxLength(120),
            Select::make('setting_type')
                ->label('Tipe')
                ->options([
                    'text' => 'Text',
                    'textarea' => 'Textarea',
                    'image' => 'Image',
                    'url' => 'URL',
                    'json' => 'JSON',
                    'color' => 'Color',
                ])
                ->required()
                ->live()
                ->default('text'),
            ImageUpload::make('image_upload', 'settings', 'Upload image')
                ->visible(fn (Get $get): bool => $get('setting_type') === 'image')
                ->helperText('Isi salah satu: upload gambar atau gunakan URL gambar. JPG, PNG, WEBP maksimal 10MB.'),
            TextInput::make('image_url')
                ->label('Image URL')
                ->url()
                ->rules(['nullable', new AllowedExternalUrl])
                ->maxLength(2048)
                ->visible(fn (Get $get): bool => $get('setting_type') === 'image')
                ->helperText('Opsional. Jika diisi, URL ini diprioritaskan dari upload.'),
            Textarea::make('setting_value')
                ->label('Value')
                ->rows(5)
                ->rules(fn (Get $get): array => $get('setting_type') === 'url' ? ['nullable', new InternalOrAllowedExternalUrl] : [])
                ->visible(fn (Get $get): bool => $get('setting_type') !== 'image'),
            LandingPagePreview::formPreview('home'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('setting_key')
            ->columns([
                TextColumn::make('setting_key')->label('Key')->searchable()->sortable(),
                TextColumn::make('setting_type')->label('Tipe')->badge(),
                TextColumn::make('setting_value')->label('Value')->limit(60)->searchable(),
            ])
            ->recordActions([
                LandingPagePreview::action(),
                EditAction::make()
                    ->mutateRecordDataUsing(fn (array $data): array => static::hydrateImageInputs($data))
                    ->mutateDataUsing(fn (array $data): array => static::resolveSettingInputs($data)),
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
            'index' => ManageSiteSettings::route('/'),
        ];
    }

    public static function hydrateImageInputs(array $data): array
    {
        if (($data['setting_type'] ?? null) !== 'image') {
            return $data;
        }

        $value = (string) ($data['setting_value'] ?? '');

        if (MediaUrl::isRemoteUrl($value)) {
            $data['image_url'] = $value;
        } elseif (filled($value)) {
            $data['image_upload'] = MediaUrl::normalizePath($value);
        }

        return $data;
    }

    public static function resolveSettingInputs(array $data): array
    {
        if (($data['setting_type'] ?? null) !== 'image') {
            unset($data['image_upload'], $data['image_url']);

            return $data;
        }

        $url = MediaUrl::normalizeManualUrl($data['image_url'] ?? null);
        $path = MediaUrl::normalizePath($data['image_upload'] ?? null);

        if (MediaUrl::isTemporaryPath($path)) {
            throw ValidationException::withMessages([
                'image_upload' => 'Upload gambar belum tersimpan ke folder final. Upload ulang gambar.',
            ]);
        }

        $data['setting_value'] = $url ?: $path;

        unset($data['image_upload'], $data['image_url']);

        return $data;
    }
}
