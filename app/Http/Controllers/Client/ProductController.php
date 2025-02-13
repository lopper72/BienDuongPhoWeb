<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function blog($slug)
    {
        session_start();
        $product = Product::where('slug', '=', $slug)->first();
        
        if(isset($_SESSION['product_id']) && in_array($product->id,$_SESSION['product_id'])){
            $_SESSION['show_url_shopee'] = 'n';
        }else{
            $_SESSION['show_url_shopee'] = 'y';
        }
        $description = $product->description;
        preg_match('/<img [^>]*src="([^"]+)"/', $description, $matches);
        $imageUrl = isset($matches[1]) ? $matches[1] : '';
        return view('client.product-detail', [
            'product' => $product,
            'imageUrl' => $imageUrl
        ]);
    }

    public function checkUrlShopee(){
        session_start();
        if (!isset($_SESSION['product_id'])) {
            $_SESSION['product_id'][] = $_POST['idProduct'];
        }else {
            if (!in_array($_POST['idProduct'],$_SESSION['product_id'])) {
                $_SESSION['product_id'][] = $_POST['idProduct'];
            }
        }
        $_SESSION['show_url_shopee'] = 'n';
        return response()->json(['message' => 'completed']);
    }
}
