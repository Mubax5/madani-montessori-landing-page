<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminIpIsAllowed
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('security.admin_allowed_ips', []);

        if ($allowedIps === []) {
            return $next($request);
        }

        if (! IpUtils::checkIp($request->ip() ?? '', $allowedIps)) {
            abort(403);
        }

        return $next($request);
    }
}
