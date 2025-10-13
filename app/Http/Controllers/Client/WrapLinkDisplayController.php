<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WrapLink;

class WrapLinkDisplayController extends Controller
{
    
    public function wraplink($slug)
    {
        $product = WrapLink::where('slug', '=', $slug)->first();

        if (!$product) {
            abort(404, 'Product not found');
        }
        
        $description = $product->description;

        // Use absolute URL for image from host
        $imageUrl2 = asset('storage/images/wraplinks/' . $product->logo);
        return view('client.wraplink', [
            'product' => $product,
            'imageUrl2' => $imageUrl2
        ]);
    }

}
