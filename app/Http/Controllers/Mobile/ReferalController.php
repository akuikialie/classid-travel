<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Authentication;
use App\Jobs\Referal\AddNewInvitedPerson;
use App\Jobs\Referal\CreateReferalLink;
use App\Models\Referal\ReferalLink;
use App\Providers\RouteServiceProvider;
use App\Services\ReferalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferalController extends Controller
{

    protected ReferalService $referalService;

    public function __construct(ReferalService $referalService)
    {
        $this->referalService = $referalService;
    }

    public function referal($hashableId, $auth = 'login')
    {
        try {
            if ($auth == 'login') {
                return view('pages.mobile.referal.referal-login-index', ['hash' => $hashableId]);
            } else if ($auth == 'register') {
                return view('pages.mobile.referal.referal-register-index', ['hash' => $hashableId]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function referalAuth(Request $request, $hashableId, $auth = 'login')
    {
        $invitation = ReferalLink::query()
            ->with(['package', 'createdBy'])
            ->where('hash', $hashableId)->first();
        if ($auth == 'login') {
            return $this->authStore($request, $invitation);
        } else if ($auth == 'register') {
            return $this->registerStore($request, $invitation);
        }
    }

    public function saved($hashableId)
    {
        DB::beginTransaction();
        try {
            $invitation = ReferalLink::query()->where('hash', $hashableId)->first();

            $this->referalService->saveInvitedPerson($invitation);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            notify('Oops!', $th->getMessage(), 'error');
            return redirect()->back();
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

        if (request()->ajax()) {
            DB::beginTransaction();
            try {
                $invitedLink = $this->referalService->createReferalLink($validator);
                DB::commit();
                return response()->json([
                    'link' => collect($invitedLink)->toArray()['link'],
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
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

        $this->referalService->saveInvitedPerson($referalLink);

        notify('Selamat!', "Kamu telah mengikuti program `{$referalLink->package->name}` bersama {$referalLink->createdBy->name}", 'success');

        return redirect(route('home.index'));
    }
}
