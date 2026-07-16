<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is an admin
        if (! $request->user() || ! $request->user()->is_admin) {
            abort(403, 'Unauthorized action.'); // Throws a 403 Forbidden error
        }

        return $next($request);
    }
}
