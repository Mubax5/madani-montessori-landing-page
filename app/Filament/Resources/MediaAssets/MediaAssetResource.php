<?php

namespace App\Filament\Resources\MediaAssets;

use App\Filament\Resources\Concerns\AdminResourceAccess;
use App\Filament\Resources\MediaAssets\Pages\ManageMediaAssets;
use App\Filament\Support\LandingPagePreview;
use App\Models\MediaAsset;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
            FileUpload::make('file_upload')
                ->label('Upload file')
                ->disk(config('filesystems.default', 'public'))
                ->directory('media')
                ->visibility('public')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(2048)
                ->openable()
                ->downloadable()
                ->helperText('Pakai ini kalau FILESYSTEM_DISK sudah memakai object storage/S3 di Laravel Cloud.'),
            TextInput::make('file_url')
                ->label('URL gambar')
                ->url()
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
                ImageColumn::make('url')
                    ->label('Preview')
                    ->getStateUsing(fn (MediaAsset $record): string => str_starts_with($record->url, 'http') ? $record->url : url($record->url))
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
                    ->mutateRecordDataUsing(fn (array $data): array => static::hydrateMediaInputs($data))
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

    public static function hydrateMediaInputs(array $data): array
    {
        $path = (string) ($data['file_path'] ?? '');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            $data['file_url'] = $path;
        } elseif (filled($path)) {
            $data['file_upload'] = $path;
        }

        return $data;
    }

    public static function resolveMediaInputs(array $data): array
    {
        $url = trim((string) ($data['file_url'] ?? ''));
        $upload = static::normalizeUploadState($data['file_upload'] ?? null);
        $path = filled($url) ? $url : $upload;

        if (blank($path)) {
            throw ValidationException::withMessages([
                'file_upload' => 'Upload file atau isi URL gambar.',
                'file_url' => 'Upload file atau isi URL gambar.',
            ]);
        }

        $data['file_path'] = $path;

        unset($data['file_upload'], $data['file_url']);

        return $data;
    }

    private static function normalizeUploadState(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = Arr::first($state);
        }

        return filled($state) ? (string) $state : null;
    }
}
