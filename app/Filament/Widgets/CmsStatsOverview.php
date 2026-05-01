<?php

namespace App\Filament\Widgets;

use App\Models\GalleryItem;
use App\Models\Lead;
use App\Models\Program;
use App\Models\TrainingEvent;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CmsStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Madani CMS';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftaran', Lead::query()->count())
                ->icon(Heroicon::OutlinedInboxStack)
                ->color('primary'),
            Stat::make('Leads Baru', Lead::query()->where('status', 'baru')->count())
                ->icon(Heroicon::OutlinedBellAlert)
                ->color('warning'),
            Stat::make('Galeri Aktif', GalleryItem::query()->where('is_active', true)->count())
                ->icon(Heroicon::OutlinedPhoto)
                ->color('success'),
            Stat::make('Program Aktif', Program::query()->where('is_active', true)->count())
                ->icon(Heroicon::OutlinedAcademicCap)
                ->color('info'),
            Stat::make('Event Published', TrainingEvent::query()->where('status', 'published')->count())
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('gray'),
        ];
    }
}
