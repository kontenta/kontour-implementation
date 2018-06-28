<?php

namespace Kontenta\AdminManagerImplementation\Http\Middleware;

use Closure;
use Kontenta\AdminManager\Contracts\AdminGuestMiddleware;
use Illuminate\Support\Facades\Auth;
use Kontenta\AdminManager\Contracts\AdminRouteManager;

class RedirectIfAuthenticated implements AdminGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard(config('admin.guard'))->check()) {
            return redirect(app(AdminRouteManager::class)->indexUrl());
        }

        return $next($request);
    }
}
