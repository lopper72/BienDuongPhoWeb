<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Brand;
use App\Models\Rate;
use App\Models\Comment;
use App\Models\BookMark;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        parse_str(parse_url($request_uri, PHP_URL_QUERY), $query_params);
        if(isset($query_params['order'])){
            return $this->order($query_params['order']);
        }else{
            $products = Product::where('is_active', '=', '1')->orderBy('created_at', 'desc')->paginate(21);
            return view('client.product', ['products' => $products, 'order' => '']);
        }
    }
    public function trend()
    {
        $top_products = Product::where('is_active', '=', '1')->orderBy('id', 'desc')->limit(8)->get();
        $trend_products = Product::where('is_active', '=', '1')->orderBy('view', 'desc')->orderBy('name', 'asc')->paginate(21);
        return view('client.trend', [
            'top_products' => $top_products,
            'trend_products' => $trend_products
        ]);
    }
    public function detail($slug)
    {
        $product = Product::where('slug', '=', $slug)->first();
        $chaps = ProductDetail::where('product_id', '=', $product->id)->orderBy('number_order','desc')->get();
        $trend_products = Product::where('is_active', '=', '1')->orderBy('id', 'asc')->limit(4)->get();
        $list_brands =  json_decode(str_replace('"', '', $product->brand_ids), true);
        $comments = Comment::where('product_id', '=', $product->id)->orderBy('created_at', 'desc')->get();
        $rateDetail = Rate::select('product_id', Rate::raw('avg(rate) as total_rate'))
            ->where('product_id', '=', $product->id)
            ->groupBy('product_id')->get();
        $brands = [];
        for ($i=0; $i < count($list_brands); $i++) { 
            $brands[] = Brand::where('id', '=', $list_brands[$i])->orderBy('name', 'asc')->get();
        }
        $checkBookMarkYN = 'n';
        if (Auth::user()) {
            $checkBookMark = BookMark::where('product_id', '=', $product->id)->where('user_id', '=', Auth::user()->id)->first();
            if (isset($checkBookMark)) {
                $checkBookMarkYN = 'y';
            }
        }
        return view('client.product-detail', [
            'product' => $product,
            'chaps' => $chaps,
            'trend_products' => $trend_products,
            'brands' => $brands,
            'comments' => $comments,
            'rateDetail' => $rateDetail,
            'checkBookMarkYN' => $checkBookMarkYN
        ]);
    }
    public function chap($slug,$number)
    {
        session_start();
        $product = Product::where('slug', '=', $slug)->first();
        if(isset($_SESSION['product_id']) && in_array($product->id,$_SESSION['product_id'])){
            $_SESSION['show_url_shopee'] = 'n';
        }else{
            $_SESSION['show_url_shopee'] = 'y';
        }
        $chap = ProductDetail::where('product_id', '=', $product->id)->where('number_order', '=', $number)->first();
        $chaps = ProductDetail::where('product_id', '=', $product->id)->orderBy('number_order', 'asc')->get();
        $comments = Comment::where('product_id', '=', $product->id)->orderBy('created_at', 'desc')->get();
        $updateViews = Product::find($product->id);
        $updateViews->view = $product->view + 1;
        $updateViews->save();
        
        if (Auth::user()) {
            $checkHistory = History::where('product_id', '=', $product->id)->where('user_id', '=', Auth::user()->id)->first();
            if (isset($checkHistory)) {
                $history = History::find($checkHistory->id);
                $history->product_detail_id = $chap->id;
                $history->updated_at = Carbon::now();
                $history->save();
            }else{
                $history = new History();
                $history->user_id = Auth::user()->id;
                $history->product_id = $product->id;
                $history->product_detail_id = $chap->id;
                $history->save();
            }
            $history = History::where('user_id', '=', Auth::user()->id)->orderBy('updated_at', 'asc')->get();
            if (count($history) > 10) {
                $historyRemove = History::find($history[0]->id);
                $historyRemove->delete();
            }
        }

        return view('client.content',[
            'product' => $product,
            'chap' => $chap,
            'chaps' => $chaps,
            'comments' => $comments
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

    public function order($order)
    {   
        switch ($order) {
            case 'atoz':
                $columnOrder = "name";
                $statusOrder = "asc";
                break;
            case 'ztoa':
                $columnOrder = "name";
                $statusOrder = "desc";
                break;
            case 'view':
                $columnOrder = "view";
                $statusOrder = "desc";
                break;
            default:
                $columnOrder = "created_at";
                $statusOrder = "desc";
                break;
        }
        $products = Product::where('is_active', '=', '1')->orderBy($columnOrder, $statusOrder)->paginate(21);
        return view('client.product', ['products' => $products,'order' => $order]);
    }

    public function brand($brandSlug){
        $brand = Brand::where('slug', '=', $brandSlug)->first();
        $products = Product::where('brand_ids','LIKE', "%{$brand->id}%")
        ->where('is_active', '=', '1')
        ->orderBy('created_at', 'desc')->paginate(21);
        return view('client.brand', ['products' => $products, 'brandName' => $brand->name]);
    }

    public function comment(Request $request){
        $rateNumber = $request->input('fmiRate');
        $commentText = $request->input('fmiCommentText');
        $productId = $request->input('fmiProductId');
        $userId = $request->input('fmiUserId');

        $checkCommnet = Comment::where('product_id', '=', $productId)->where('user_id', '=', $userId)->first();

        if (isset($checkCommnet)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã bình luận truyện này.<br> Xin cảm ơn!',
            ]);
        }

        $comment = new Comment();
        $comment->user_id = $userId;
        $comment->product_id = $productId;
        $comment->comment = $commentText;
        $rate = new Rate();
        $rate->user_id = $userId;
        $rate->product_id = $productId;
        $rate->rate = $rateNumber;

        $comment->save();
        $rate->save();

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá thành công.<br> Cảm ơn bạn đánh giá của bạn.',
        ]);
    }

    public function bookmark(Request $request){
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để lưu truyện.',
            ]);
        }else{
            $checkBookMark = BookMark::where('product_id', '=', $request['productIdDetail'])->where('user_id', '=', Auth::user()->id)->first();
            if (isset($checkBookMark)) {
                $checkBookMark->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Bạn đã gỡ đánh dấu truyện thành công.',
                ]);
            }else{
                $bookmark = new BookMark();
                $bookmark->user_id =  Auth::user()->id;
                $bookmark->product_id = $request['productIdDetail'];
                $bookmark->product_detail_id = 0;
                $bookmark->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Bạn đã đánh dấu truyện thành công.',
                ]);
            }
        }
    }

    public function itemBookmark(){
        if (Auth::user()) {
            $bookmarks = BookMark::where('user_id', '=', Auth::user()->id)->paginate(21);
            return view('client.bookmark', ['bookmarks' => $bookmarks]);
        }
    }
}
