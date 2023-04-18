<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct(){
        $product = Product::where('stock','>',0)->where('estado','A')->
        with('category:id,category')->get();
        return response()->json($product,200);
    }
}
