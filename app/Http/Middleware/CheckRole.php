<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->session()->get('user');

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        if (! in_array($user->role, $roles)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
