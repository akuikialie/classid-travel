<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Authentication;
use App\Jobs\Referal\AddNewInvitedPerson;
use App\Jobs\Referal\CreateReferalLink;
use App\Models\Referal\ReferalLink;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ReferalController extends Controller
{
    public function referal($hashableId, $auth = 'login')
    {
        try {
            if ($auth == 'login') {
                return view('pages.mobile.referal.referal-login-index', ['hash' => $hashableId]);
            }else if ($auth == 'register') {
                return view('pages.mobile.referal.referal-register-index', ['hash' => $hashableId]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function referalAuth(Request $request, $hashableId, $auth = 'login')
    {
        $invitation = ReferalLink::query()->where('hash', $hashableId)->first();
        if ($auth == 'login') {
            // return Redirect::action([ReferalController::class, 'authStore'], $request);
            return $this->authStore($request, $invitation);
        }else if ($auth == 'register') {
            return $this->registerStore($request, $invitation);
        }
    }

    public function saved($hashableId)
    {
        try {
            $invitation = ReferalLink::query()->where('hash', $hashableId)->first();

            $this->dispatch(new AddNewInvitedPerson($invitation));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'package_id' => ['required', 'integer', 'exists:plan_packages,id'],
            'expired_status' => ['nullable'],
        ], [
            'package_id.required' => 'Paket tidak boleh kosong!',
            'package_id.exists' => 'Paket yang dipilih tidak ditemukan!',
        ]);

        try {
            if (request()->ajax()) {
                $invitedLink = new CreateReferalLink($validator);
                $data = $invitedLink->handle();
                return response()->json([
                    'link' => collect($data)->toArray()['link'],
                ]);
            }else{
                // $this->dispatch(new CreateReferalLink($validator));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function registerStore(Request $request, ReferalLink $referalLink)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users,phone'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        // $validator = array_merge( $validator, ['password' => Hash::make($validator['password'])]);

        // User::query()->create($validator);

        return redirect(route('login'));

    }

    public function authStore(Request $request, ReferalLink $referalLink)
    {
        $newRequest = new Authentication;
        $newRequest->authenticate();

        $request->session()->regenerate();

        $this->dispatch(new AddNewInvitedPerson($referalLink));

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
