<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::OrderBy('id','DESC')->get();

        return view("front.index")->with(compact('products'));
    }

    public function get_product($id)
    {
        $product = Product::find($id);
        return $product;
    }

}
