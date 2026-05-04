<?php

namespace App\Filament\Resources\MediaAssets;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\MediaAssets\Pages\ManageMediaAssets;
use App\Filament\Support\ImageUpload;
use App\Filament\Support\LandingPagePreview;
use App\Models\MediaAsset;
use App\Support\MediaUrl;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class MediaAssetResource extends Resource
{
    use AdminResourceAccess;

    protected static ?string $model = MediaAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?string $navigationLabel = 'Media Library';

    protected static ?string $modelLabel = 'Media';

    protected static ?string $pluralModelLabel = 'Media Library';

    protected static ?string $recordTitleAttribute = 'alt_text';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ImageUpload::make('file_path', 'media', 'Upload file')
                ->helperText('JPG, PNG, atau WEBP maksimal 10MB. File disimpan dengan nama acak.'),
            TextInput::make('file_url')
                ->label('URL gambar')
                ->url()
                ->rules(['nullable', 'url', 'starts_with:http://,https://'])
                ->maxLength(2048)
                ->helperText('Opsional. Jika diisi, URL ini akan dipakai dan mengabaikan upload file.'),
            TextInput::make('alt_text')
                ->label('Alt text')
                ->required()
                ->maxLength(180),
            Textarea::make('caption')
                ->label('Caption')
                ->rows(3),
            LandingPagePreview::formPreview('galeri'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alt_text')
            ->columns([
                ImageColumn::make('image_final_url')
                    ->label('Preview')
                    ->getStateUsing(fn (MediaAsset $record): ?string => $record->image_final_url)
                    ->defaultImageUrl(MediaUrl::placeholderDataUri('Media'))
                    ->imageSize(52)
                    ->square(),
                TextColumn::make('alt_text')->label('Alt text')->searchable(),
                TextColumn::make('file_name')->label('File')->searchable(),
                TextColumn::make('mime_type')->label('MIME'),
                TextColumn::make('created_at')->label('Upload')->dateTime('d M Y H:i')->sortable(),
            ])
            ->recordActions([
                LandingPagePreview::action('galeri'),
                EditAction::make()
                    ->mutateDataUsing(fn (array $data): array => static::resolveMediaInputs($data)),
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
            'index' => ManageMediaAssets::route('/'),
        ];
    }

    public static function resolveMediaInputs(array $data): array
    {
        $data['file_url'] = MediaUrl::normalizeManualUrl($data['file_url'] ?? null);
        $data['file_path'] = MediaUrl::normalizePath($data['file_path'] ?? null);

        if (MediaUrl::isTemporaryPath($data['file_path'])) {
            throw ValidationException::withMessages([
                'file_path' => 'Upload file belum tersimpan ke folder final. Upload ulang file.',
            ]);
        }

        if (blank($data['file_url']) && blank($data['file_path'])) {
            throw ValidationException::withMessages([
                'file_path' => 'Upload file atau isi URL gambar.',
                'file_url' => 'Upload file atau isi URL gambar.',
            ]);
        }

        return $data;
    }
}
