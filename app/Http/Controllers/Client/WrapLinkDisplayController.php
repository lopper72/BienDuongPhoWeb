<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WrapLink;

class WrapLinkDisplayController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.wraplink.index');
    }

    public function add()
    {
        return view('admin.dashboard.wraplink.add_wraplink');
    }

    public function delete($id){
        $wraplink = WrapLink::find($id);
        $wraplink->delete();
        return redirect()->route('admin.wraplinks');
    }
    public function wraplink($slug)
    {
        session_start();
        $product = WrapLink::where('slug', '=', $slug)->first();
        
        //$this->setShowUrlShopee($product->id);
        //$this->setShowUrlTiktok($product->id);

        // Retrieve existing videos
        $existingVideos = json_decode($product->image, true) ?: []; // Decode JSON to array or return empty array

        $description = $product->description;
        preg_match('/<img [^>]*src="([^"]+)"/', $description, $matches);
        $imageUrl = isset($matches[1]) ? $matches[1] : '';
        return view('client.wraplink', [
            'product' => $product,
            'imageUrl' => $imageUrl,
            'existingVideos' => $existingVideos // Pass existing videos to the view
        ]);
    }

    public function edit($id)
    {
        $wraplink = WrapLink::find($id);
        return view('admin.dashboard.wraplink.edit_wraplink', ['wraplink' => $wraplink]);
    }
}
