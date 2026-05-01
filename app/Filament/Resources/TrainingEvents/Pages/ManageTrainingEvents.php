<?php

namespace App\Filament\Resources\TrainingEvents\Pages;

use App\Filament\Resources\TrainingEvents\TrainingEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTrainingEvents extends ManageRecords
{
    protected static string $resource = TrainingEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => TrainingEventResource::canCreate()),
        ];
    }
}
