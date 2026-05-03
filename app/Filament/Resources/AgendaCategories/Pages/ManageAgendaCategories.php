<?php

namespace App\Filament\Resources\AgendaCategories\Pages;

use App\Filament\Resources\AgendaCategories\AgendaCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAgendaCategories extends ManageRecords
{
    protected static string $resource = AgendaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => AgendaCategoryResource::canCreate()),
        ];
    }
}
