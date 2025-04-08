<?php

namespace App\Http\Middleware;

use App\Enums\Permissions\RegisterPermissions;
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
        $method = match ($request->method()) {
            'GET',
            'HEAD' => 'view',
            'POST' => 'create',
            'PATCH',
            'PUT' => 'update',
            'DELETE' => 'delete',
            default => null,
        };

        if ($this->isAllowedUsingPage(action: $method, page: $page)) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions([]);
    }

    private function isAllowedUsingPage(string $action, string $page): bool
    {
        $user = auth()->user();

        foreach (RegisterPermissions::cases() as $permissionRegistered) {
            if ($permissionRegistered->usingOnPage() != $page){
                continue;
            }
            foreach ($permissionRegistered->value::cases() as $permission) {
                if ($permission->usesFor() != $action) {
                    continue;
                }

                if ($action && $user->can($permission->value)){
                    return true;
                }
            }
        }
        return false;
    }
}
