<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        if (!(env(key: 'ADMIN_URL') === \request()->host())){
            abort(404);
        }

    }
}
