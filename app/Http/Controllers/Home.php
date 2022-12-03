<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Ajax;

use App\Models\Attribute;
use App\Models\Feature;
use App\Models\Needmap;
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
            $results = Sell::join('products', 'sells.product_id', '=', 'products.id')
                           ->where('vendor_id', $user->id)
                           ->get();
        } else {
            $results = Own::join('products', 'owns.product_id', '=', 'products.id')
                          ->where('customer_id', $user->id)
                          ->get();
        }
        return view('listWrapper', ['products' => $results, 'user' => $user, 'deletes' => true]);
    }

    public function exactSearchPage(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }

        return view('exactSearch', ['user' => $user]);
    }

    public function rankedSearchPage(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }

        return view('rankedSearch', ['user' => $user]);
    }

    public function needSearchPage(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }

        $needmaps = Needmap::all();

        return view('needSearch', ['user' => $user, 'needmaps' => $needmaps]);
    }

    public function needmapPage(Request $request) {
        $user = Auth::user();
        if(is_null($user) || $user->username != 'admin') {
            return redirect('/');
        }else{
            return view('addNeedmap', ['user' => $user]);
        }
    }

    public function productlist(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
             return redirect('/');
        }else{

        if($user->type == 'customer'){

           $productlist = DB::table('products')-> get();
           return view('productlist', ['productlist'=>$productlist],['user' => $user]);
        }else
        echo('vendor');
            $selllist = DB::table('sells')-> get();
            $productlist = DB::table('products')-> get();
           
           /* while($selllist->product_id == $productlist->id){

            }

            */
            //just lists all products right now even for vendor
            return view('productlist', ['productlist'=>$productlist],['user' => $user]);
            
           
           ;
           

            
           /*
           if ($request->has('productlist')) {              

                if($user->type == 'vendor'){
                  
                    $productlist = DB::table('sells')-> get();
                    return view('productlist', ['productlist'=>$productlist],['user' => $user]);
                    
                }
                else{
                    echo('hasuser2');
                    $productlist = DB::table('products')-> get();
                    return view('productlist', ['productlist'=>$productlist],['user' => $user]);
                }
 
                #not sure if I can even do this? Need a way to tell if customer wants to list owns or products
            }elseif($request->has('ownslist')){

                if($user->type == 'customer'){
                    $ownslist = DB::table('owns')-> get();
                    $productlist = DB::table('products')-> get();
                   // return view('productlist','ownslist','user','request', ['productlist'=>$productlist],['ownslist'=> $ownslist],['user'=> $user],['request' => $request]);
                }
            }
*/        
    }
    }

    

}

