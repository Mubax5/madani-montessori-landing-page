<?php

namespace App\Filament\Resources\BimbelPackages\Pages;

use App\Filament\Resources\BimbelPackages\BimbelPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBimbelPackages extends ManageRecords
{
    protected static string $resource = BimbelPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => BimbelPackageResource::canCreate()),
        ];
    }
}
