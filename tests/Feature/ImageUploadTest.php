<?php

namespace Tests\Feature;

use App\Filament\Support\ImageUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use ReflectionMethod;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    public function test_uploaded_file_is_copied_to_verified_final_path(): void
    {
        Storage::fake('public');

        config([
            'livewire.temporary_file_upload.directory' => 'livewire-tmp',
        ]);

        Storage::disk('public')->put('livewire-tmp/source.png', 'image-bytes');

        $file = new TemporaryUploadedFile('source.png', 'public');
        $component = FileUpload::make('cover_image_path')
            ->disk('public')
            ->directory('agendas')
            ->getUploadedFileNameForStorageUsing(fn (): string => 'final.png');

        $method = new ReflectionMethod(ImageUpload::class, 'storeFinalFile');
        $method->setAccessible(true);

        $finalPath = $method->invoke(null, $component, $file);

        $this->assertSame('agendas/final.png', $finalPath);
        Storage::disk('public')->assertExists('agendas/final.png');
        $this->assertSame('image-bytes', Storage::disk('public')->get('agendas/final.png'));
    }
}
