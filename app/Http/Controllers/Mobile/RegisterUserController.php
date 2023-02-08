<?php

namespace App\Http\Controllers\Mobile;

use App\Actions\Users\CreateNewUser;
use App\Enums\RoleEnum;
use App\Models\Jamaah\JamaahHistory;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RegisterUserController extends Controller
{

    public function create()
    {
        return view('pages.mobile.auth.register-index');
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users,phone'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        // User::query()->create($validator);
        DB::beginTransaction();
        try {

            /* begin:: user service */
            $tenant = activeTenant();
            $userService = new UserService(tenantId: $tenant->id);
            $user = $userService
                ->createNewUser($validator)
                ->setRole(RoleEnum::Jamaah->keyValue())
                ->createVa('tabungan')
                ->setDepartureStatus()
                ->getUser();
            /* end:: user service */

            DB::commit();

            \Auth::login($user);

            notify('Berhasil!!', 'Anda berhasil membuat akun, silahkan login menggunakan akun anda', 'success');
            return redirect()->intended(route('login'));
        }catch (Throwable $e){
            DB::rollBack();
            logError($e, title: 'Mobile Register');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }
}
