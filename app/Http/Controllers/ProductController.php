<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data['title'] = " - Product";
        return view('product.index', $data);
    }
}
