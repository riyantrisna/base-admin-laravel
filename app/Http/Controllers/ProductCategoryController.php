<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $data['title'] = " - ".multi_lang('product_category');
        return view('product-category.index', $data);
    }
}
