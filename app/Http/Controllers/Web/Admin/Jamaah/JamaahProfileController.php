<?php

namespace App\Http\Controllers\Web\Admin\Jamaah;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Http\Controllers\Web\Admin\Controller;
use App\Models\User;
use App\Services\UserService;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Prefix;
use Dentro\Yalr\Attributes\Put;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Http\Request;

#[Prefix('jamaah')]
#[Name('jamaah', false, true)]
#[Middleware(['auth:sanctum'])]
class JamaahProfileController extends Controller
{

    protected string $forPage = 'jamaah-profile';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBreadCrumb(['title' => 'Jamaah Profil', 'url' => routed('admin.jamaah-profil.index')]);
        $this->setData('current_page', $this->forPage);
    }

    private function default(User $user): void
    {
        $user->loadMissing(['tabungan']);
        $user->loadCount('transactions');
        $this->setData('user', $user);
        $this->setData('saving', $user->tabungan);
    }

    /**
     * @param User $user
     * @return View
     * @throws Exception
     */
    #[Get('{user}/detail', name: 'show')]
    public function show(User $user): View
    {
        $this->setPageTitle('Profil Jamaah');
        $this->setBreadCrumb('Profil Jamaah');

        $this->default($user);

        return $this->view('pages.web.jamaah-profile.show');
    }

    /**
     * @param User $user
     * @return View
     * @throws Exception
     */
    #[Get('{user}/overview', name: 'overview')]
    public function overview(User $user): View
    {
        $this->setPageTitle('Profil Overview');
        $this->setBreadCrumb('Profil Overview');

        $this->default($user);

        return $this->view('pages.web.jamaah-profile.fragment.overview');
    }

    /**
     * @param User $user
     * @return View
     * @throws Exception
     */
    #[Get('{user}/savings', name: 'savings')]
    public function savings(User $user): View
    {
        $this->setPageTitle('Tabungan');
        $this->setBreadCrumb('Tabungan');

        $this->default($user);

        $this->setData('packageSavings', $user->jamaah->tabunganPackages);

        return $this->view('pages.web.jamaah-profile.fragment.savings');
    }

    /**
     * @param User $user
     * @return View
     * @throws Exception
     */
    #[Get('{user}/transactions', name: 'transactions')]
    public function transactions(User $user): View
    {
        $this->setPageTitle('Transaksi & Mutasi');
        $this->setBreadCrumb('Transaksi & Mutasi');

        $this->default($user);

        $transactionMethods = TransactionMethod::cases();
        $transactionTypes = TransactionType::cases();

        $this->setData('transactionMethods', $transactionMethods);
        $this->setData('transactionTypes', $transactionTypes);

        return $this->view('pages.web.jamaah-profile.fragment.transactions');
    }

    /**
     * @param User $user
     * @return View
     * @throws Exception
     */
    #[Get('{user}/mutations', name: 'mutations')]
    public function mutations(User $user): View
    {
        $this->setPageTitle('Transaksi & Mutasi');
        $this->setBreadCrumb('Transaksi & Mutasi');

        $this->default($user);

        return $this->view('pages.web.jamaah-profile.fragment.mutations');
    }

        /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws HandleCatchableException
     * @throws Throwable
     */
    #[Put('{user}/updatePassword', name: 'updatePassword')]
    public function updatePassword(Request $request, User $user)
    {
        try {
            $input = $request->validate([
                'password' => ['required'],
                'confirm_password' => ['required_with:password', 'same:password'],
            ]);

            (new UserService())
                ->setUser($user)
                ->update(['password' => Hash::make($input['password'])]);

            notify('Berhasil', 'Password berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'calon jamaah - update password');
            if (isDevelopmentMode()) {
                throw $e;
            }
            $message = 'Terjadi kesalahan!';
            if ($e->getCode() >= 900) {
                $message = $e->getMessage();
            }
            notify('Oops!', $message, 'error');

            return redirect()->back();
        }
    }


}
