<?php

namespace App\Providers;

use App\Models\AdminUser;
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
use App\Support\AuditLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
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
            AdminUser::class,
        ] as $model) {
            $model::observe(AuditObserver::class);
        }

        Event::listen(Login::class, function (Login $event): void {
            if ($event->guard === 'admin' && method_exists($event->user, 'forceFill')) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();

                AuditLogger::write(
                    action: 'login_success',
                    module: 'admin_auth',
                    recordId: $event->user->getAuthIdentifier(),
                    newData: ['email' => $event->user->email],
                    actor: $event->user instanceof AdminUser ? $event->user : null,
                );
            }
        });

        Event::listen(Failed::class, function (Failed $event): void {
            if ($event->guard !== 'admin') {
                return;
            }

            $email = (string) ($event->credentials['email'] ?? '');

            AuditLogger::write(
                action: 'login_failed',
                module: 'admin_auth',
                recordId: $event->user?->getAuthIdentifier(),
                newData: [
                    'email_hash' => $email !== '' ? hash('sha256', strtolower($email)) : null,
                    'known_user' => $event->user instanceof AdminUser,
                ],
                actor: $event->user instanceof AdminUser ? $event->user : null,
            );
        });

        Event::listen(Logout::class, function (Logout $event): void {
            if ($event->guard !== 'admin' || ! $event->user) {
                return;
            }

            AuditLogger::write(
                action: 'logout',
                module: 'admin_auth',
                recordId: $event->user->getAuthIdentifier(),
                newData: ['email' => $event->user->email],
                actor: $event->user instanceof AdminUser ? $event->user : null,
            );
        });
    }
}
