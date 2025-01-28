<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function refund()
    {
        return view("storefront.legal.refund");
    }

    public function terms()
    {
        return view("storefront.legal.terms");
    }

    public function privacy()
    {
        return view("storefront.legal.privacy");
    }
}
