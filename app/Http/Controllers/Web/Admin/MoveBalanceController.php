<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\User;
use App\Services\MoveBalanceService;
use App\Traits\FragmentRenderer;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

#[Prefix('move-balance')]
#[Name('move-balance', false, true)]
#[Middleware(['auth:sanctum'])]
class MoveBalanceController extends Controller
{
    use FragmentRenderer;

    protected string $forPage = 'moveb-balance';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @param Request $request
     * @return View
     * @throws Exception
     */
    #[Get('', name: 'index')]
    public function index(Request $request): View
    {
        $this->setPageTitle('Move Balance');
        return $this->view('pages.web.move-balance.index');
    }

    #[Post('move', name: 'move')]
    public function moveBalance(Request $request, MoveBalanceService $service)
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $service->moveBalance(actor: $user, inputs: $request->input());

            notify('Berhasil', 'Berhasil memindahkan saldo', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'move-balance - store');
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
