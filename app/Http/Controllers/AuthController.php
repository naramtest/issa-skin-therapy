<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }

    public function register()
    {
        return view("auth.register");
    }

    public function myAccount()
    {
        return view("auth.myAccount", ["user" => \Auth::user()]);
    }
}
