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
        $validator = $request->validate([
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        // User::query()->create($validator);
        DB::beginTransaction();
        try {

            var_dump($request->all());
           
            // notify('Berhasil!!', 'Anda berhasil membuat akun, silahkan login menggunakan akun anda', 'success');
            return redirect()->intended(route('login'));
        } catch (\Exception $e) {
            DB::rollBack();
            logError($e, title: 'Mobile Reset Password');

            throw_if(isDevelopmentMode(), $e);
            toSentry($e);

            notify('Oops!', 'Terjadi kesalahan!', 'error');

            return back()->withInput();
        }
    }
}
