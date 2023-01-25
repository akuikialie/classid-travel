<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $prefix = request()->route()->getPrefix();
            if (str_contains($prefix, 'admin')) {
                return route('admin.login');
            }
            return route('login');
        }
        abort(404);
    }
}
