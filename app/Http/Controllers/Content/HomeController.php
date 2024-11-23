<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view("storefront.home");
    }
}
