<?php

namespace App\Http\Controllers\Web\Admin\Jamaah;

use App\Http\Controllers\Web\Admin\Controller;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Prefix;
use Exception;
use Illuminate\Contracts\View\View;

#[Prefix('jamaah')]
#[Name('jamaah', false, true)]
#[Middleware(['auth:sanctum'])]
class JamaahController extends Controller
{
    protected string $forPage = 'jamaah';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBreadCrumb(['title' => 'Data Jamaah ', 'url' => routed('admin.jamaah.index')]);
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return View
     * @throws Exception
     */
    #[Get('', name: '')]
    public function show(): View
    {
        $this->setPageTitle('Data Jamaah');
        $this->setBreadCrumb('Data Jamaah');

        return $this->view('pages.web.jamaah.show');
    }

}
