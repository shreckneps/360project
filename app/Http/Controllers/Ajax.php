<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Feature;
use App\Models\Own;
use App\Models\Product;
use App\Models\Sell;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Ajax extends Controller {

    public function addProduct(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return "Authentication error.";
        }
        $prod = new Product;
        $prod->name = $request->pname;
        $prod->type = $request->ptype;
        $prod->save();

        for($i = 0; $i < $request->numAtr; $i++) {
            if($request->has('atrval' . $i)) {
                $atr = new Attribute;
                $atr->product_id = $prod->id;
                $atr->name = $request->input('atr' . $i);
                $atr->value = $request->input('atrval' . $i);
                $atr->save();
            }
        }

        for($i = 0; $i < $request->numFtr; $i++) {
            if($request->has('ftrval' . $i)) {
                $ftr = new Feature;
                $ftr->product_id = $prod->id;
                $ftr->name = $request->input('ftr' . $i);
                $ftr->value = $request->input('ftrval' . $i);
                $ftr->save();
            }
        }

        if($user->type == 'customer') {
            $own = new Own;
            $own->customer_id = $user->id;
            $own->product_id = $prod->id;
            $own->save();
        } else {
            $sell = new Sell;
            $sell->vendor_id = $user->id;
            $sell->product_id = $prod->id;
            $sell->price = $request->sprice;
            $sell->save();
        }
        
        return "Product added.";
    }

    public function listify(Request $request) {
        $num = $request->num;
        $arr = array();
        for($i = 0; $i < $num; $i++) {
            $arr[$i] = $request->input('val' . $i);
        }
        return view('listing', ['arr' => $arr]);
    }

    public function getFormAtr(Request $request) {
        return view('form.attribute', ['num' => $request->num]);
    }

    public function getFormFtr(Request $request) {
        return view('form.feature', ['num' => $request->num]);
    }

}


