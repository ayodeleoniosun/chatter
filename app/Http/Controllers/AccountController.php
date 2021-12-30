<?php

namespace App\Http\Controllers;

use http\Env\Request;

class AccountController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function register()
    {
        dd($this->request->all());
    }
}
