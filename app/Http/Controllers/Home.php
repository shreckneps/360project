<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Ajax;

use App\Models\Attribute;
use App\Models\Feature;
use App\Models\Own;
use App\Models\Product;
use App\Models\Sell;
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

    public function showListings(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }

        if($user->type == 'vendor') {

        } else {
            $owns = Own::where('customer_id', $user->id);
            echo '<hr>';
            var_dump($owns->pluck('product_id'));
            echo '<hr>';
            for($i = 0; $i < 100; $i++) {
                echo $i . ': ' . $owns->pluck('product_id')->contains($i) . '<br>';
            }
            echo '<hr>';
            $var = 1;
            $results = Product::all()->filter(function ($prod, $index) use ($owns) {
                return $owns->pluck('product_id')->contains($prod->id);
            });

        }

        foreach($results as $index => $listing) {
            echo $index . ': ' . $listing->name . ' ';
            echo $listing->type . '<br>';
            echo '<hr>';
        }

        //return view('listWrapper', ['productList' => $results, 'user' => $user]);
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
                    return view('productlist','user', ['productlist'=>$productlist],['user' => $user]);
                    
                }
                else{
                   echo('UserClient');
                    $productlist = DB::table('products')-> get();
                    return view('productlist','user', ['productlist'=>$productlist],['user' => $user]);
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

