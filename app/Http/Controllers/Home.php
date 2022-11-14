<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Accounts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
       
    }

    public function dynamic(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }
        if($request->has('num')) {
            return view('dynamicRecursive', ['num' => $request->num]);
        } else {
            return view('dynamic', ['user' => $user]);
        }
    }

    public function addProduct(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }
        return view('addProduct', ['user' => $user]); 
    }

    public function productlist(Request $request) {
 
        $user = Auth::user();
        if(is_null($user)) {
            echo('noUser');
             return redirect('/');
        }else{
            echo('hasUser');
            if ($request->has('productlist')) {              

                if($user->type == 'vendor'){
                  
                    $productlist = DB::table('sells')-> get();
                    return view('productlist', ['productlist'=>$productlist],['user' => $user],);
                    
                }
                else{
                   echo('UserClient');
                    $productlist = DB::table('products')-> get();
                    return view('productlist', ['productlist'=>$productlist]);
                }
 
            }elseif($request->has('ownslist')){
                if($user->type == 'customer'){
                    $ownslist = DB::table('owns')-> get();
                    $productlist = DB::table('products')-> get();
                    return view('productlist','ownslist','user','request', ['productlist'=>$productlist],['ownslist'=> $ownslist],['user'=> $user],['request' => $request]);
                }
            }
        
    }
    }

    

}

