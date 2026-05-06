<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProductionAdminMfaIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isProduction()) {
            return $next($request);
        }

        $user = auth()->guard('admin')->user();

        if (! $user instanceof AdminUser || $user->hasEnabledMfa() || $this->isMfaSetupRoute($request)) {
            return $next($request);
        }

        return redirect()->guest(url(config('security.admin_panel_path', 'admin').'/multi-factor-authentication/set-up'));
    }

    private function isProduction(): bool
    {
        return app()->isProduction() || config('app.env') === 'production';
    }

    private function isMfaSetupRoute(Request $request): bool
    {
        $adminPath = trim((string) config('security.admin_panel_path', 'admin'), '/');

        return $request->is($adminPath.'/multi-factor-authentication*');
    }
}
