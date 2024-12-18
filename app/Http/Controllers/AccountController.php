<?php

namespace App\Http\Controllers;

use Auth;

class AccountController extends Controller
{
    //TODO: add last order , edit addresses
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        return view("storefront.account.show", ["user" => $user]);
    }

    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        return view("storefront.account.edit", ["user" => $user]);
    }
}
