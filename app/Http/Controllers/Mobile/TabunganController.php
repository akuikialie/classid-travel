<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Invoication\Invocation;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Queries\MutationQuery;
use App\Queries\TransactionQuery;
use App\Services\EWallet\WalletService;
use App\Services\Saving\SavingService;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
            if ($wallet->login($virtualAccount->va_number, $virtualAccount->password)) {
                $invoice = $wallet->createInvoice($request->get('amount'));

                return redirect()->back()->with(['invoice' => $invoice]);
            }
        } catch (Throwable $e) {
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
                    'savings' => 'Rp ' . number_format($saving->balance ?? 0),
                    'usd_savings' => '$ ' . number_format($saving->usd_balance ?? 0),
                ];
                break;

            case 'perencanaan':
                $name = 'tabungan ' . $saving?->myPackage->name;
                $userSaving = [
                    'namaTabungan' => ucwords($name ?? 'NN'),
                    'id' => $saving->id,
                    'va' => $saving->va_number,
                    'savings' => 'Rp ' . number_format($saving->balance ?? 0),
                    'usd_savings' => '$ ' . number_format($saving->usd_balance ?? 0),
                    'targetSavings' => 'Rp ' . number_format($saving->myPackage?->amount ?? 0),
                ];
                break;

            default:
                # code...
                break;
        }

        \request()->mergeIfMissing([
            'mutable_id' => $saving->hash,
            'mutable_type' => VirtualAccount::class,
            'latest' => true,
        ]);

        $mutations = MutationQuery::filterColumn()
            ->orderColumn()
            ->build()
            ->limit(5)
            ->get();

        $saving->loadMissing('virtualAccountMutations');

        return view('pages.mobile.tabungan.tabungan-show', [
            'saving' => $saving,
            'moneybox' => collect($userSaving),
            'mutations' => $mutations,
            'convertBalanceMutations' => $saving->virtualAccountMutations,
        ]);
    }
}
