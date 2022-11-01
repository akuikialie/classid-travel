<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Auth\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationSessionController extends Controller
{
    public function splash()
    {
        $tenant = activeTenant();
        return view('pages.mobile.splashscreen-index', ['tenant' => $tenant]);
    }

    public function create()
    {
        return view('pages.mobile.auth.login-index');
    }

    public function store(Authentication $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('home.index'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /* redirect to login page */
        return redirect(url('/'));
    }
}
