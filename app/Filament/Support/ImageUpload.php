<?php

namespace App\Filament\Support;

use App\Support\MediaUrl;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            ->rules(['image', 'mimetypes:image/jpeg,image/png,image/webp', 'dimensions:max_width=4096,max_height=4096'])
            ->maxSize(10240)
            ->storeFiles()
            ->saveUploadedFileUsing(fn (BaseFileUpload $component, TemporaryUploadedFile $file): ?string => self::storeFinalFile($component, $file))
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

    private static function storeFinalFile(BaseFileUpload $component, TemporaryUploadedFile $file): ?string
    {
        $diskName = $component->getDiskName();
        $finalPath = trim(($component->getDirectory() ?: '').'/'.$component->getUploadedFileNameForStorage($file), '/');
        $stream = null;

        try {
            if (! $file->exists()) {
                throw new \RuntimeException("Temporary upload no longer exists for [{$diskName}:{$finalPath}].");
            }

            $stream = $file->readStream();

            if (! is_resource($stream)) {
                throw new \RuntimeException("Unable to read temporary upload stream for [{$diskName}:{$finalPath}].");
            }

            $stored = Storage::disk($diskName)->put($finalPath, $stream, [
                'visibility' => 'public',
            ]);

            if (! $stored) {
                throw new \RuntimeException("Unable to write uploaded file to [{$diskName}:{$finalPath}].");
            }

            try {
                Storage::disk($diskName)->setVisibility($finalPath, 'public');
            } catch (\Throwable $e) {
                report($e);
            }

            if (! Storage::disk($diskName)->exists($finalPath)) {
                throw new \RuntimeException("Uploaded file was not found after final write [{$diskName}:{$finalPath}].");
            }

            return $finalPath;
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                $component->getStatePath(false) ?: $component->getStatePath() ?: 'file' => 'File berhasil masuk temporary upload, tetapi gagal disimpan ke folder final. Coba upload ulang atau cek konfigurasi storage.',
            ]);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }
}
