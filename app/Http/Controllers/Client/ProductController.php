<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function blog($slug)
    {
        $product = Product::where('slug', '=', $slug)->first();
        return view('client.product-detail', [
            'product' => $product
        ]);
    }
}
