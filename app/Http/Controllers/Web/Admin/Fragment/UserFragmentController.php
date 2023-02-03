<?php

namespace App\Http\Controllers\Web\Admin\Fragment;

use App\Http\Controllers\Controller;

class UserFragmentController extends Controller
{
    public function overview()
    {
        $this->addGlobalParams('fragment_view', 'pages.web.user.fragment.fragment-overview');

    }

    public function setting()
    {
        $this->addGlobalParams('fragment_view', 'pages.web.user.fragment.fragment-overview');

//        $this->addGlobalParams('fragment_view', 'pages.web.user.fragment.fragment-setting');
    }
}
