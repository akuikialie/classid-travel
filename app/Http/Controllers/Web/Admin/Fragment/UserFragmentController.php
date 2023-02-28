<?php

namespace App\Http\Controllers\Web\Admin\Fragment;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserFragmentController extends Controller
{
    public function overview()
    {
        // return view('pages.web.user.fragment.fragment-overview');
        $this->addGlobalParams('fragment_view', 'pages.web.user.fragment.fragment-overview');

    }

    public function setting()
    {
        $this->addGlobalParams('fragment_view', 'pages.web.user.fragment.fragment-setting');

//        $this->addGlobalParams('fragment_view', 'pages.web.user.fragment.fragment-setting');
    }
}
