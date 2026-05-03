<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Models\AgendaRegistration;
use App\Models\GalleryItem;
use App\Models\Lead;
use App\Models\Program;
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
            Stat::make('Total Agenda', Agenda::query()->count())
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('gray'),
            Stat::make('Agenda Published', Agenda::query()->where('status', 'published')->count())
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('gray'),
            Stat::make('Agenda Terdekat', Agenda::query()->where('status', 'published')->where('start_at', '>=', now()->startOfDay())->count())
                ->icon(Heroicon::OutlinedBellAlert)
                ->color('success'),
            Stat::make('Pendaftar Agenda', AgendaRegistration::query()->count())
                ->icon(Heroicon::OutlinedTicket)
                ->color('primary'),
            Stat::make('Pendaftar Baru Agenda', AgendaRegistration::query()->where('status', 'new')->count())
                ->icon(Heroicon::OutlinedInboxStack)
                ->color('warning'),
        ];
    }
}
