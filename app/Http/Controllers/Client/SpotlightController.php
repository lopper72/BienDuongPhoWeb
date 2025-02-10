<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

class SpotlightController extends Controller
{
    public function index()
    {   
        $products = Product::where('is_active', '=', '1')
        ->where('name','LIKE', "%{$_GET['keyword']}%")
        ->orWhere('description','LIKE', "%{$_GET['keyword']}%")
        ->orWhereNull('is_active')
        ->orderBy('created_at', 'desc')->paginate(21);
        return view('client.search-result', ['products'=>$products]);
    }
    public function search()
    {
        if (isset($_GET['input_search'])) {
            $input_search = $_GET['input_search'];
        }else{
            $input_search = $_GET['inputSearchIndex'];
        }
        
        return redirect()->route('search_result',['keyword'=>$input_search]);
    }
}
