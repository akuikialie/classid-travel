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
        switch ($request->method()) {
            case 'GET':
                if ($user->can("view {$page}")) {
                    return $next($request);
                }
                break;

            case 'POST':
                if ($user->can("create {$page}")) {
                    return $next($request);
                }
                break;

            case 'PUT':
                if ($user->can("update {$page}")) {
                    return $next($request);
                }
                break;

            case 'DELETE':
                if ($user->can("delete {$page}")) {
                    return $next($request);
                }
                break;

            default:
                break;
        }
        throw UnauthorizedException::forPermissions([]);
    }
}
