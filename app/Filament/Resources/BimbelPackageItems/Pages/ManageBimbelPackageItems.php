<?php

namespace App\Filament\Resources\BimbelPackageItems\Pages;

use App\Filament\Resources\BimbelPackageItems\BimbelPackageItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBimbelPackageItems extends ManageRecords
{
    protected static string $resource = BimbelPackageItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => BimbelPackageItemResource::canCreate()),
        ];
    }
}
