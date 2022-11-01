<?php

namespace App\Http\Controllers\Web\Admin\Fragment;

use App\Http\Controllers\Controller;
use App\Traits\ViewSupport;
use Illuminate\Http\Request;

class TenantFragmentController extends Controller
{
    use ViewSupport;
    public function overview()
    {

    }

    public function metadata()
    {

    }

    public function setting()
    {
        $this->setGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-setting');
    }
}
