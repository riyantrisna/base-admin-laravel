<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data['title'] = " - ".multi_lang('product');
        return view('product.index', $data);
    }
}
