<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Base\Media;
use Illuminate\Http\Request;
use App\Models\Tenant\TenantData;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\Auth\Authentication;
use Illuminate\Contracts\Foundation\Application;

class AuthenticationSessionController extends Controller
{
    public function create(): Factory|View|Application
    {
        return view('pages.web.auth.sign-in');    
    }

    /**
     * @param Authentication $request
     * @return RedirectResponse|void
     */
    public function store(Authentication $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = \auth()->user();

        if ($user->hasRole('administrator')){
            return redirect()->intended(route('admin.dashboard'));
        }
        if ($user->hasRole('super-administrator')){
            return redirect()->intended(route('admin.tenant.index'));
        }
        abort(404);
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
