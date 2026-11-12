<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // not an admin -> stop with "403 Forbidden"
    if (! $request->user() || ! $request->user()->isAdmin()) {
        abort(403, 'Only admins can open this page.');
    }

    return $next($request);
}
}
