<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if (
            !app()->runningInConsole() &&
            env(key: 'ADMIN_URL') === request()->host() &&
            !preg_match('/^admin(\/.*)?/i', request()->path())
        ){
            return to_route('dashboard.admin')->send();
        }
    }
}
