<?php

namespace App\Models\Concerns;

use App\Support\MediaUrl;

trait HasFileUrls
{
    protected function resolveFileUrl(?string $path = null, ?string $manualUrl = null, ?string $disk = null): ?string
    {
        return MediaUrl::resolve($path, $manualUrl, $disk);
    }
}
