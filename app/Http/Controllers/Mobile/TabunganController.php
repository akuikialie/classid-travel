<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Invoication\Invocation;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Services\EWallet\WalletService;
use App\Services\Saving\SavingService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class TabunganController extends Controller
{

    public function index(SavingService $service)
    {
        /** @var User $user */
        $user = auth()->user();
        /* begin:: show all savings */
        $savings = $service->getListSavings($user);
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
            logError($e, title: 'Mobile tabungan');
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
    public function billing(VirtualAccount $saving)
    {
        //call the inquiry
        $this->setData('va', $saving);
        return $this->view('pages.mobile.tabungan.tabungan-billing');
    }

    public function show(VirtualAccount $saving)
    {
        switch ($saving->va_label) {
            case 'tabungan':
                $userSaving = [
                    'id' => $saving->id,
                    'va' => $saving->va_number,
                    'savings' => $saving->balance,
                ];
                break;

            case 'perencanaan':
                $name = 'tabungan ' . $saving?->myPackage->name;
                $userSaving = [
                    'namaTabungan' => ucwords($name ?? 'NN'),
                    'id' => $saving->id,
                    'va' => $saving->va_number,
                    'savings' => $saving->balance,
                    'targetSavings' => 'Rp ' . number_format($saving->myPackage?->amount ?? 0),
                ];
                break;

            default:
                # code...
                break;
        }

        $invocations = Invocation::query()
            ->where('virtual_account', '=', $saving->va_number)
            ->get();

        return view('pages.mobile.tabungan.tabungan-show', [
            'moneybox' => collect($userSaving),
            'invocations' => $invocations,
        ]);
    }
}
