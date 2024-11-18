<?php

namespace App\Http\Controllers;

class ProductCategoryController extends Controller
{
    public function index(string $slug)
    {
        return view("storefront.product.category");
    }
}
