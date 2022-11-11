<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\RoleEnum;
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

class AuthenticationSessionController extends Controller
{
    public function create(): Factory|View|Application
    {

        return view('pages.web.auth.sign-in');
    }

    public function store(Authentication $request)
    {

        $request->authenticate();

        $request->session()->regenerate();

        $user = \auth()->user();

        if ($user->hasRole('administrator')){
            $tenant = Tenant::query()
                ->where('BCN', request()->input('travel_code'))
                ->first();

            if (!$tenant){
                notify('Gagal!', trans('auth.failed'), 'error');
                return  redirect()->back()->withInput();
            }

            if ($user->tenant_id == $tenant->id){
                return redirect()->intended(route('admin.dashboard'));
            }else{
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                notify('Gagal!', trans('auth.failed'), 'error');
                return redirect()->back()->withInput();
            }
        }else if ($user->hasRole('super-administrator')){
            return redirect()->intended(route('admin.tenant.index'));
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
