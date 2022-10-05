<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('pages.mobile.auth.register-index');
    }

    public function store(Request $request)
    {

    }
}
