<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserClientController extends Controller
{
    public function index()
    {   
        if (isset(Auth::user()->id)) {
            return view('client.info_user');
        }else{
            return redirect()->route('login');
        }
    }

    public function login(Request $request){
        $email = $request->input('fmiEmailLogIn');
        $password = $request->input('fmiPasswordLogIn');
        
        if (empty($email) || empty($password)) {
            return response()->json(['message' => 'Email và mật khẩu không được để trống.'], 400);
        }

        $credentials = ['email' => $email, 'password' => $password];
        if (auth()->attempt($credentials)) {
            return redirect()->route('index');
        } else {
            return response()->json(['message' => 'Thông tin đăng nhập không chính xác.'], 400);
        }
    }

    public function signup(Request $request){
        $name = $request->input('fmiNameSignUp');
        $email = $request->input('fmiEmailSignUp');
        $password = $request->input('fmiPasswordSignUp');
        
        if (empty($name) || empty($email) || empty($password)) {
            return response()->json(['message' => 'Tên, email và mật khẩu không được để trống.'], 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => $email.' không phải là email hợp lệ.'], 400);
        }
        if (strlen($password) < 6) {
            return response()->json(['message' => 'Mật khẩu phải có ít nhất 6 ký tự.'], 400);
        }
        $user = User::where('role','customer')->where('email','=',$email)->first();
        if (isset($user)) {
            return response()->json(['message' => 'Email đã tồn tại.'], 400);
        }

        $id_latest = User::where('role','customer')->get();
        $user_code = 'CUS-'.str_pad(count($id_latest)+ 1, 4, '0', STR_PAD_LEFT);
        $parts = explode('@', $email);
        $username = $parts[0].'-'.count($id_latest)+ 1;

        $user = new User();
        $user->code = $user_code;
        $user->name = $name;
        $user->username = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_active = 1;
        $user->role = 'customer';
        $user->save();

        $credentials = ['email' => $email, 'password' => $password];
        if (auth()->attempt($credentials)) {
            return redirect()->route('index');
        }
    }
}
