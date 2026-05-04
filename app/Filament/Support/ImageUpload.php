<?php

namespace App\Filament\Support;

use App\Support\MediaUrl;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageUpload
{
    public static function make(string $name, string $directory, ?string $label = null): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk(MediaUrl::defaultDisk())
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->rules(['image', 'mimetypes:image/jpeg,image/png,image/webp'])
            ->maxSize(10240)
            ->storeFiles()
            ->moveFiles()
            ->previewable()
            ->openable()
            ->downloadable()
            ->getUploadedFileNameForStorageUsing(fn (TemporaryUploadedFile $file): string => Str::ulid().'.'.self::extensionForMime($file->getMimeType()));
    }

    private static function extensionForMime(?string $mime): string
    {
        return match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }
}
