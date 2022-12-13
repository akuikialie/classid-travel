<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Services\EWallet\WalletService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class TabunganController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        /* begin:: show all savings */
        $savings = $this->listSavings($user);
        /* end:: show all savings */

        return view('pages.mobile.tabungan.tabungan-index', [
            'list_moneyboxs' => $savings,
        ]);
    }

    /**
     * @param VirtualAccount $virtualAccount
     * @return string
     */
    public function create(VirtualAccount $virtualAccount)
    {
        return '';
    }

    /**
     * @param Request $request
     * @param VirtualAccount $virtualAccount
     * @return RedirectResponse
     * @throws Exception|Throwable
     */
    public function createInvoice(Request $request, VirtualAccount $virtualAccount)
    {
        try {
            $wallet = new WalletService();
            if ($wallet->login($virtualAccount->va_number, $virtualAccount->password)){
                $invoice = $wallet->createInvoice($request->get('amount'));

                return redirect()->back()->with(['invoice' => $invoice]);
            }
        }catch (Throwable $e){
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
        }
        return redirect()->back();

    }

    /**
     * @throws Exception
     */
    public function billing(VirtualAccount $virtualAccount)
    {
        $this->setData('va', $virtualAccount);
        return $this->view('pages.mobile.tabungan.tabungan-billing');
    }

    public function show(VirtualAccount $virtualAccount)
    {

        switch ($virtualAccount->va_label) {
            case 'tabungan':
                $saving = [
                    'id' => $virtualAccount->id,
                    'va' => $virtualAccount->va_number,
                ];
                break;

            case 'perencanaan':
                $name = 'tabungan ' . $virtualAccount?->myPackage->name;
                $saving = [
                    'namaTabungan' => ucwords($name ?? 'NN'),
                    'id' => $virtualAccount->id,
                    'va' => $virtualAccount->va_number,
                    'targetSavings' => 'Rp ' . number_format($virtualAccount->myPackage?->amount ?? 0),
                ];
                break;

            default:
                # code...
                break;
        }
        return view('pages.mobile.tabungan.tabungan-show', [
            'moneybox' => collect($saving),
        ]);
    }


    private function listSavings(User $authUser)
    {
        $savings = collect([]);
        /* begin:: main savings */
        $user = User::query()
            ->with(['tabungan'])
            ->where('id', '=', $authUser->id)
            ->first();

        $mainSaving = [
            'id' => $user->tabungan->hash,
            'va' => $user->tabungan->va_number,
            'showDetails' => true,
        ];

        $savings->add($mainSaving);
        /* end:: main savings */

        /* begin:: planing savings */
        $jamaah = Jamaah::query()
            ->with(['tabunganPackages.myPackage.myPlan'])
            ->where('user_id', '=', $authUser->id)
            ->first();

        $planingSavings = [];
        foreach ($jamaah->tabunganPackages as $tabungan) {

            $namaTabungan = 'tabungan ' . $tabungan?->myPackage->name;
            $savings->add([
                'namaTabungan' => ucwords($namaTabungan),
                'id' => $tabungan->hash,
                'va' => $tabungan->va_number,
                'targetSavings' => $tabungan?->myPackage?->amount ?? 0,
                'showDetails' => true,
            ]);
        }
        /* end:: planing savings */

        return $savings;
    }
}
