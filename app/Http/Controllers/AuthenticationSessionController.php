<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\Authentication;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class AuthenticationSessionController extends Controller
{
    public function create()
    {
        return view('pages.mobile.auth.login-index');
    }

    public function store(Authentication $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request)
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /* redirect to login page */
    }
}
