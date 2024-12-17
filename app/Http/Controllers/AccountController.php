<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function show()
    {
        return view("storefront.account.show", ["user" => \Auth::user()]);
    }

    public function edit()
    {
        return view("storefront.account.edit", ["user" => \Auth::user()]);
    }
}
