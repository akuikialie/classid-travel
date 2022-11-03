<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();

//        if(!isActiveTenant()) {
//            abort(404);
//        }
    }
}
