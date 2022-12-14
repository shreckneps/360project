<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Ajax;

use App\Models\Attribute;
use App\Models\Feature;
use App\Models\Needmap;
use App\Models\Offer;
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
        return view('listWrapper', ['user' => $user]);
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

    public function datagenPage(Request $request) {
        $user = Auth::user();
        if(is_null($user) || $user->username != 'admin') {
            return redirect('/');
        }else{
            return view('datagen', ['user' => $user]);
        }
    }

    public function testPage(Request $request) {
        return view('testGeneric');
    }

    public function showOffers(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return redirect('/');
        }

        $offers;
        $self;
        $other;
        if($user->type == 'customer') {
            $self = 'customer_status';
            $other = 'vendor_status';
            $offers = Offer::where('customer_id', $user->id);
        } else {
            $self = 'vendor_status';
            $other = 'customer_status';
            $offers = Offer::where('vendor_id', $user->id);
        }
        $offers = $offers->where($self, '!=', 'rejected')
                         ->where($self, '!=', 'finalized');
        
        $minProd = Product::select('id as product_id', 'name');
        $offers = $offers->joinSub($minProd, 'products', function ($join) {
            $join->on('offers.product_id', '=', 'products.product_id');
        })->get();
        foreach($offers as $offer) {
            if($offer[$other] == 'waiting') {
                $offer->status = 'Waiting';
            } else {
                $offer->status = 'Action Needed';
            }
        }
        $offers = $offers->sortBy('status');

        return view('offerList', ['user' => $user, 'offers' => $offers, 'self' => $self, 'other' => $other]);
    }

}

