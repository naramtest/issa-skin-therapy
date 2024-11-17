<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view("storefront.home");
    }
}
