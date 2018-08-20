<?php

namespace App\Http\Middleware;

use App\Tenant\TenantManager;
use Closure;

class DefineAuthGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var TenantManager $tenantManager */
        $tenantManager = app(TenantManager::class);
        if (!$tenantManager->getTenant()) {
            abort(404);
        }
        return $next($request);
    }
}
