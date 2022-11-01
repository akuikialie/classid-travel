<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Authentication;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthenticationSessionController extends Controller
{
    public function create(): Factory|View|Application
    {
        return view('pages.web.auth.sign-in');
    }

    public function store(Authentication $request)
    {
        $tenant = Tenant::query()
            ->where('BCN', request()->input('travel_code'))
            ->first();

        if (!$tenant){
            notify('Gagal!', trans('auth.failed'), 'error');
            return  redirect()->back()->withInput();
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = \auth()->user();
        if ($user->tenant_id == $tenant->id){
            return redirect()->intended(route('dashboard.admin'));
        }else{
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            notify('Gagal!', trans('auth.failed'), 'error');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Request $request): Redirector|Application|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /* redirect to login page */
        return redirect(route('admin.login'));
    }
}
