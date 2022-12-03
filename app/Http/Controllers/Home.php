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
            $sells = Sell::where('vendor_id', $user->id)->get();
            $results = Product::all()->filter(function ($prod, $index) use ($sells) {
                return $sells->pluck('product_id')->contains($prod->id);
            });
            foreach($results as $result) {
                $result->price = $sells->firstWhere('product_id', $result->id)->price;
            }
        } else {
            $owns = Own::where('customer_id', $user->id)->get();
            $results = Product::all()->filter(function ($prod, $index) use ($owns) {
                return $owns->pluck('product_id')->contains($prod->id);
            });
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

