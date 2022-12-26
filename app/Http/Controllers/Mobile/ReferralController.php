<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Authentication;
use App\Models\Referral\ReferralLink;
use App\Services\ReferralService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReferralController extends Controller
{
    /**
     * @param $hashableId
     * @param $auth
     * @return Application|Factory|View|void
     * @throws Throwable
     */
    public function referral($hashableId, $auth = 'login')
    {
        try {
            if ($auth == 'login') {
                return view('pages.mobile.referal.referal-login-index', ['hash' => $hashableId]);
            }
            if ($auth == 'register') {
                return view('pages.mobile.referal.referal-register-index', ['hash' => $hashableId]);
            }
        } catch (Throwable $e) {
            logError($e, title: 'Mobile referral');
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param $hashableId
     * @param $auth
     * @return Application|RedirectResponse|Redirector|void
     */
    public function referralAuth(Request $request, $hashableId, $auth = 'login')
    {
        $invitation = ReferralLink::query()
            ->with(['package', 'createdBy'])
            ->where('hash', $hashableId)->first();
        if ($auth == 'login') {
            return $this->authStore($request, $invitation);
        }
        if ($auth == 'register') {
            return $this->registerStore($request, $invitation);
        }
    }

    /**
     * @throws Throwable
     */
    public function saved($hashableId)
    {
        DB::beginTransaction();
        try {
            $invitation = ReferralLink::query()->where('hash', $hashableId)->first();

            $this->referalService->saveInvitedPerson($invitation);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Mobile referral');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request) // ajax
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
                $user = auth()->user();
                $referralService = new ReferralService(tenantId: $user->tenant_id);
                $invitedLink = $referralService->createReferralLink($validator, $user);
                DB::commit();
                return response()->json([
                    'link' => collect($invitedLink)->toArray()['link'],
                ]);
            } catch (Throwable $e) {
                DB::rollBack();
                logError($e, title: 'Mobile referral');
                if (isDevelopmentMode()) {
                    throw $e;
                } else {
                   throw new \Exception('terjadi kesalahan!.');
                }
            }
        }
    }

    public function registerStore(Request $request, ReferralLink $referralLink)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users,phone'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        DB::beginTransaction();
        try {
            $userService = new UserService(tenantId: $referralLink->tenant_id);
            $newUser = $userService
                ->createNewUser($validator)
                ->setRole(RoleEnum::Jamaah->keyValue())
                ->createVa('tabungan')
                ->setDepartureStatus()
                ->getUser();

            /* end:: user service */

            $referralService = new ReferralService(tenantId: $referralLink->tenant_id);
            $referralService->saveInvitedPerson($referralLink, $newUser);
            DB::commit();

            notify('Selamat!', "Kamu telah berhasil membuat akun dan mengikuti program `{$referralLink->package->name}` bersama {$referralLink->createdBy->name}", 'success');

            return redirect(route('login'));
        }catch (Throwable $e){
            DB::rollBack();
            logError($e, title: 'Mobile referral');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
    public function authStore(Request $request, ReferralLink $referralLink)
    {
        try {
            $newRequest = new Authentication();
            $newRequest->authenticate();

            $request->session()->regenerate();

            $referralService = new ReferralService(tenantId: $referralLink->tenant_id);
            $referralService->saveInvitedPerson($referralLink, auth()->user());

            notify('Selamat!', "Kamu telah mengikuti program `{$referralLink->package->name}` bersama {$referralLink->createdBy->name}", 'success');

            return redirect(route('home.index'));
        } catch (Throwable $e) {
            logError($e, title: 'Mobile referral');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }
}
