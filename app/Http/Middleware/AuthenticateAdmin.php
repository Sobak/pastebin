<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        if (
            empty(config('auth.admin.username'))
            || empty(config('auth.admin.password'))
            || $request->getUser() !== config('auth.admin.username')
            || $request->getPassword() !== config('auth.admin.password')
        ) {
            throw new UnauthorizedHttpException('Basic', 'Invalid credentials');
        }

        return $next($request);
    }
}
