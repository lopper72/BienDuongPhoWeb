<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function forgot_password(){
        return view('client.forgot_password');
    }
    public function index()
    {   
        $products = Product::where('is_active', '=', '1')->orWhereNull('is_active')->orderBy('created_at', 'desc')->paginate(18);
        return view('client.index', [
            'products' => $products
        ]);
    }
}
