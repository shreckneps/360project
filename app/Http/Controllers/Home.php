<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class Home extends Controller {

    public function dashboard(Request $request) {
        if ($request->has('logout')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        $user = Auth::user();
        if(is_null($user)) {
            return view('home');
        }
        return view('dashboard', ['user' => $user]);
        
        if($request->has('productlist')){
            return view('productlist');
        }
    }

    public function login(Request $request) {
        if(!is_null(Auth::user())) {
            return redirect('/');
        }
        if ($request->has('login')) {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect('/');
            } else {
                echo 'Login failed: incorrect username/password. <br>';
            }
        }
        return view('login');
    }

    public function register(Request $request) {
        if(!is_null(Auth::user())) {
            return redirect('/');
        }
        if ($request->has('register')) {
            if($request->password == $request->password_conf) {
                $existing = User::where('username', $request->username)->count();
                if($existing == 0) {
                    $new = new User;
                    $new->username = $request->username;
                    $new->password = Hash::make($request->password);
                    $new->type = $request->register;
                    $new->save();

                    Auth::attempt(['username' => $request->username, 'password' => $request->password]);
                    $request->session()->regenerate();
                    return redirect('/');
                } else {
                    echo 'Username already taken. <br>';
                }
            } else {
                echo 'Passwords must match. <br>';
            }
        }
        return view('register');
    }

    public function productlist(Request $request) {
        echo 'List of all products';
       
        if ($request->has('productlist')) {
            $productlist = DB::table('products')-> get();
          return view('productlist', ['productlist'=>$productlist]);
    
        }else{
            echo'other';
        }
    
            
        
    }     
}

