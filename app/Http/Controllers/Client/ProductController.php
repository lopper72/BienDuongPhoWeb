<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    private function setShowUrlShopee($productId) {
        if (isset($_SESSION['product_id_shopee']) && in_array($productId, $_SESSION['product_id_shopee'])) {
            $_SESSION['show_url_shopee'] = 'n';
        } else {
            $_SESSION['show_url_shopee'] = 'y';
        }
    }

    private function setShowUrlTiktok($productId) {
        if (isset($_SESSION['product_id_tiktok']) && in_array($productId, $_SESSION['product_id_tiktok'])) {
            $_SESSION['show_url_tiktok'] = 'n';
        } else {
            $_SESSION['show_url_tiktok'] = 'y';
        }
    }

    public function blog($slug)
    {
        session_start();
        $product = Product::where('slug', '=', $slug)->first();
        
        $this->setShowUrlShopee($product->id);
        $this->setShowUrlTiktok($product->id);

        // Retrieve existing videos
        $existingVideos = json_decode($product->image, true) ?: []; // Decode JSON to array or return empty array

        $description = $product->description;
        preg_match('/<img [^>]*src="([^"]+)"/', $description, $matches);
        $imageUrl = isset($matches[1]) ? $matches[1] : '';
        return view('client.product-detail', [
            'product' => $product,
            'imageUrl' => $imageUrl,
            'existingVideos' => $existingVideos // Pass existing videos to the view
        ]);
    }

    public function checkUrlShopee(){
        session_start();
        if (!isset($_SESSION['product_id_shopee'])) {
            $_SESSION['product_id_shopee'][] = $_POST['idProductShopee'];
        }else {
            if (!in_array($_POST['idProductShopee'],$_SESSION['product_id_shopee'])) {
                $_SESSION['product_id_shopee'][] = $_POST['idProductShopee'];
            }
        }
        $_SESSION['show_url_shopee'] = 'n';
        return response()->json(['message' => 'completed']);
    }

    public function checkUrlTiktok(){
        session_start();
        if (!isset($_SESSION['product_id_tiktok'])) {
            $_SESSION['product_id_tiktok'][] = $_POST['idProductTikTok'];
        }else {
            if (!in_array($_POST['idProductTikTok'],$_SESSION['product_id_tiktok'])) {
                $_SESSION['product_id_tiktok'][] = $_POST['idProductTikTok'];
            }
        }
        $_SESSION['show_url_tiktok'] = 'n';
        return response()->json(['message' => 'completed']);
    }

    public function resolveRedirect(Request $request)
    {
           // $url = $request->input('url'); // Cách này cũng được với Laravel >= 5.4
    $url = $request->json('url'); // Đảm bảo lấy từ body JSON
    if (!$url) return response()->json(['error' => 'No url'], 400);

        try {
            $client = new \GuzzleHttp\Client(['allow_redirects' => true, 'timeout' => 10]);
            $response = $client->get($url, ['http_errors' => false]);
            $redirects = $response->getHeader(\GuzzleHttp\RedirectMiddleware::HISTORY_HEADER);
            $finalUrl = end($redirects) ?: $url;
            return response()->json(['final_url' => $finalUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
