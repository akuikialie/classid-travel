<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RedirectIfHasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $page
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $page)
    {
        $user = auth()->user();
        $method = match ($request->method()) {
            'GET',
            'HEAD' => 'view',
            'POST' => 'create',
            'PATCH',
            'PUT' => 'update',
            'DELETE' => 'delete',
            default => null,
        };

        if ($method && $user->can("{$method} {$page}")) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions([]);
    }
}
