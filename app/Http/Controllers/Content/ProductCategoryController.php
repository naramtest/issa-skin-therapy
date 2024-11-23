<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function index(string $slug)
    {
        return view("storefront.product.category");
    }
}
