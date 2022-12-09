<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class Accounts extends Controller {

    public function login(Request $request) {
        if(!is_null(Auth::user())) {
            return redirect('/');
        }

        $failure = null;
        if ($request->has('login')) {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect('/');
            } else {
                $failure = 'Login failed: incorrect username/password.';
            }
        }
        return view('login', ['failure' => $failure]);
    }

    public function register(Request $request) {
        if(!is_null(Auth::user())) {
            return redirect('/');
        }

        $failure = null;
        if ($request->has('register')) {
            if($request->password == $request->password_conf) {
                $existing = User::where('username', $request->username)->count();
                if($existing == 0) {
                    $new = new User;
                    $new->username = $request->username;
                    $new->password = Hash::make($request->password);
                    $new->name = $request->name;
                    $new->type = $request->register;
                    $new->save();

                    Auth::attempt(['username' => $request->username, 'password' => $request->password]);
                    $request->session()->regenerate();
                    return redirect('/');
                } else {
                    $failure = 'Registration failed: username already taken.'; 
                }
            } else {
                $failure = 'Registration failed: passwords must match.';
            }
        }
        return view('register', ['failure' => $failure]);
    }
        
}

