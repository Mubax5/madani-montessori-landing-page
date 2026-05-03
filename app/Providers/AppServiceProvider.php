<?php

namespace App\Providers;

use App\Models\Agenda;
use App\Models\AgendaCategory;
use App\Models\AgendaRegistration;
use App\Models\BimbelPackage;
use App\Models\BimbelPackageItem;
use App\Models\Faq;
use App\Models\FeaturedProgram;
use App\Models\GalleryItem;
use App\Models\Lead;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Models\WhatsappTemplate;
use App\Observers\AuditObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            Page::class,
            PageSection::class,
            MediaAsset::class,
            Program::class,
            FeaturedProgram::class,
            BimbelPackage::class,
            BimbelPackageItem::class,
            AgendaCategory::class,
            Agenda::class,
            AgendaRegistration::class,
            GalleryItem::class,
            Faq::class,
            Lead::class,
            WhatsappTemplate::class,
            SiteSetting::class,
            NavigationItem::class,
        ] as $model) {
            $model::observe(AuditObserver::class);
        }

        Event::listen(Login::class, function (Login $event): void {
            if ($event->guard === 'admin' && method_exists($event->user, 'forceFill')) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });
    }
}
