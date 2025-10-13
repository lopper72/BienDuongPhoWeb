<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WrapLink;
use App\Models\AffiliateClick;

class WrapLinkDisplayController extends Controller
{
    
    public function wraplink($slug)
    {
        $product = WrapLink::where('slug', '=', $slug)->first();

        if (!$product) {
            abort(404, 'Product not found');
        }
        
        $description = $product->description;

        // Lưu thông tin click vào database
        AffiliateClick::create([
            'affiliate_link_id' => $product->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->headers->get('referer'),
        ]);

        // Use absolute URL for image from host
        $imageUrl2 = asset('storage/images/wraplinks/' . $product->logo);
        return view('client.wraplink', [
            'product' => $product,
            'imageUrl2' => $imageUrl2
        ]);
    }

}
