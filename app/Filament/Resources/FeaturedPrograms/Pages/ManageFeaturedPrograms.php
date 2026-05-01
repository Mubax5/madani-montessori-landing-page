<?php

namespace App\Filament\Resources\FeaturedPrograms\Pages;

use App\Filament\Resources\FeaturedPrograms\FeaturedProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFeaturedPrograms extends ManageRecords
{
    protected static string $resource = FeaturedProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => FeaturedProgramResource::canCreate()),
        ];
    }
}
