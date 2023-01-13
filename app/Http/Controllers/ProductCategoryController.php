<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $data['title'] = " - Product Category";
        return view('product.index', $data);
    }
}
