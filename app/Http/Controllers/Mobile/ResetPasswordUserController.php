<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\RoleEnum;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
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
        $validator = $request->validate([
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        try {
            $user = User::where('phone', $request->phone)
                        ->first();

            if (!$user) {
                 notify('Oops!', 'User Tidak Tersedia', 'error');

                return back()->withInput();
            }

            $user->update([
                'password' => $request->password,
            ]);
           
            notify('Berhasil!!', 'Anda berhasil merubah password akun anda, silahkan login menggunakan akun anda', 'success');
            return redirect()->intended(route('login'));
        } catch (\Exception $e) {
            logError($e, title: 'Mobile Reset Password');

            throw_if(isDevelopmentMode(), $e);
            toSentry($e);

            notify('Oops!', 'Terjadi kesalahan!', 'error');

            return back()->withInput();
        }
    }
}
