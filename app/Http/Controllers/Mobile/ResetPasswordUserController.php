<?php

namespace App\Http\Controllers\Mobile;

// use App\Actions\Users\CreateNewUser;
use App\Enums\RoleEnum;
// use App\Models\Jamaah\JamaahHistory;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ResetPasswordUserController extends Controller
{

    public function index(): View
    {
        return view('pages.mobile.auth.reset-password-index');
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            return redirect()->intended(route('login'));
        } catch (\Exception $e) {

            return back()->withInput();
        }
    }
}
