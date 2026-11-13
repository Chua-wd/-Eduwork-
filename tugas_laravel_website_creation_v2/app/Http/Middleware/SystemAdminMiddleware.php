<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemAdminMiddleware
{
    /**
     * Only system admins get through (used for User management).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isSystemAdmin()) {
            abort(403, 'Only system admins can open this page.');
        }

        return $next($request);
    }
}
