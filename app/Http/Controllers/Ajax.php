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
use Illuminate\Support\Facades\DB;

class Ajax extends Controller {

    public function addListing(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return "Authentication error.";
        }

        $prod = Product::find($request->product_id);
        if(is_null($prod)) {
            return "Attempted to add invalid product. Try again or manually specify product details.";
        }

        if($user->type == 'customer') {
            $own = new Own;
            $own->customer_id = $user->id;
            $own->product_id = $prod->id;
            $own->save();
        } else {
            $existing = Sell::where('vendor_id', $user->id)
                            ->where('product_id', $prod->id);
            if($existing->count() > 0) {
                return "You cannot list a product you already sell.";
            }
            $sell = new Sell;
            $sell->vendor_id = $user->id;
            $sell->product_id = $prod->id;
            $sell->price = $request->sprice;
            $sell->save();
        }
        
        return "Product added.";
    }

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

    public function listExisting(Request $request) {
        $results = Product::where('type', $request->type)
                          ->where('name', $request->name);
        if($results->count() > 0) {
            return "<br><h3>Or Choose an Existing Product: </h3>" . view('list', ['products' => $results->get(), 'adds' => 'yes']);
        } else {
            return "";
        }
    }

    public function removeListing(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return "Authentication error.";
        }
        
        if($user->type == 'vendor') {
            Sell::where('vendor_id', $user->id)
                ->firstWhere('product_id', $request->del_id)
                ->delete();
        } else {
            Own::where('customer_id', $user->id)
               ->firstWhere('product_id', $request->del_id)
               ->delete();
        }

        //If the product is now sold and owned by noone, delete it
        if(Sell::all()->contains('product_id', $request->del_id)) {
            return;
        }
        if(Own::all()->contains('product_id', $request->del_id)) {
            return;
        }
        Product::destroy($request->del_id);
    }

    public function exactSearch(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return "Authentication error.";
        }

        $results = Sell
            ::join('products', 'sells.product_id', '=', 'products.id')
            ->where('type', $request->ptype);

        if($request->filled('pname')) {
            $results = $results->where('name', $request->pname);
        }
        
        for($i = 0; $i < $request->numAtr; $i++) {
            if($request->has('atrval' . $i)) {
                $results = $results->whereExists(function ($query) use ($request, $i) {
                    $query->select(DB::raw(1))
                          ->from('attributes')
                          ->whereColumn('attributes.product_id', 'sells.product_id')
                          ->where('attributes.name', $request->input('atr' . $i))
                          ->where('attributes.value', $request->input('atrval' . $i));
                });
            }
        }

        for($i = 0; $i < $request->numFtr; $i++) {
            if($request->has('ftrval' . $i)) {
                $results = $results->whereExists(function ($query) use ($request, $i) {
                    $query->select(DB::raw(1))
                          ->from('features')
                          ->whereColumn('features.product_id', 'sells.product_id')
                          ->where('features.name', $request->input('ftr' . $i))
                          ->where('features.value', $request->input('ftrval' . $i));
                });
            }
        }

        //return var_dump($results);
        return view('list', ['products' => $results->get()->sortBy('price'), 'user' => $user]);
    }

    public function rankedSearch(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return "Authentication error.";
        }

        $results = Sell
            ::join('products', 'sells.product_id', '=', 'products.id')
            ->where('type', $request->ptype)
            ->get();

        foreach ($results as $result) {
            $result->searchMatch = 0;
            for($i = 0; $i < $request->numFtr + $request->numAtr; $i++) {
                $result->searchMatch *= 2;

                $field = $request->input('fld' . $i);
                $ftype = substr($field, 0, 3);
                $fnum = substr($field, 3);
                if(!$request->has($ftype . 'val' . $fnum)) {
                    continue;
                }
                $fval = $request->input($ftype . 'val' . $fnum);
                $fopr = $request->input($ftype . 'opr' . $fnum);

                if($ftype == 'atr') {
                    if($field = 'Price') {
                        $fsaved = $result->price;
                    } else {
                        $fsaved = Attribute::where('name', $request->input($field))
                                           ->where('product_id', $result->product_id)
                                           ->first()
                                           ->value;
                    }
                } else {
                    $fsaved = Feature::where('name', $request->input($field))
                                     ->where('product_id', $result->product_id)
                                     ->first()
                                     ->value;
                }

                if (isset($fsaved) && $this->compareEval($fsaved, $fopr, $fval)) {
                    $result->searchMatch += 1;
                }
            }
        }
        
        $results = $results->sortBy([ ['searchMatch', 'desc'], ['price', 'asc'] ]);

        return view('list', ['products' => $results, 'user' => $user]);

    }

    private function compareEval($left, $op, $right) {
        switch($op) {
            case 'eq':
                return($left == $right);
                break;
            case 'ne':
                return($left != $right);
                break;
            case 'lt':
                return($left < $right);
                break;
            case 'gt':
                return($left > $right);
                break;
        }
    }

    public function detailList(Request $request) {
        $features = Feature::where('product_id', $request->product_id)->get();
        $attributes = Attribute::where('product_id', $request->product_id)->get();

        return view('detailList', ['details' => $features->merge($attributes)]);
    }

    public function typeList(Request $request) {
        $types = Product::select('type')->distinct()->get()->pluck('type');
        return view('form.datalist', ['options' => $types]);
    }

    public function nameList(Request $request) {
        $names = Product::where('type', $request->type)->select('name')->distinct()->get()->pluck('name');
        return view('form.datalist', ['options' => $names]);
    }

    public function attributeList(Request $request) {
        $attributes = Attribute::whereExists(function ($query) use ($request) {
            $query->select(DB::raw(1))
                  ->from('products')
                  ->whereColumn('attributes.product_id', 'products.id')
                  ->where('products.type', $request->type);
        });
        $attributes = $attributes->select('name')->distinct()->get()->pluck('name');
        if($request->has('includePrice')) {
            $attributes->prepend('Price');
        }
        return view('form.datalist', ['options' => $attributes]);
    }

    public function featureList(Request $request) {
        $features = Feature::whereExists(function ($query) use ($request) {
            $query->select(DB::raw(1))
                  ->from('products')
                  ->whereColumn('features.product_id', 'products.id')
                  ->where('products.type', $request->type);
        });
        $features = $features->select('name')->distinct()->get()->pluck('name');
        return view('form.datalist', ['options' => $features]);
    }

    public function getAtrVals(Request $request) {
        $values = Attribute::whereExists(function ($query) use ($request) { 
            $query->select(DB::raw(1))
                  ->from('products')
                  ->whereColumn('attributes.product_id', 'products.id')
                  ->where('products.type', $request->type)
                  ->where('attributes.name', $request->name);
        });
        $values = $values->select('value')->distinct()->get()->pluck('value');
        return view('form.datalist', ['options' => $values]);
    }

    public function getFtrVals(Request $request) {
        $values = Feature::whereExists(function ($query) use ($request) { 
            $query->select(DB::raw(1))
                  ->from('products')
                  ->whereColumn('features.product_id', 'products.id')
                  ->where('products.type', $request->type)
                  ->where('features.name', $request->name);
        });
        $values = $values->select('value')->distinct()->get()->pluck('value');
        return view('form.datalist', ['options' => $values]);
    }

    public function getFormAtr(Request $request) {
        return view('form.attribute', ['num' => $request->num]);
    }

    public function getFormFtr(Request $request) {
        return view('form.feature', ['num' => $request->num]);
    }

    public function getFormCmpr(Request $request) {
        $sum = $request->numAtr + $request->numFtr;
        if($request->type == 'atr') {
            return view('form.attributeComparison', ['num' => $request->numAtr, 'sum' => $sum]);
        } else {
            return view('form.featureComparison', ['num' => $request->numFtr, 'sum' => $sum]);
        }
    }

    public function listify(Request $request) {
        $num = $request->num;
        $arr = array();
        for($i = 0; $i < $num; $i++) {
            $arr[$i] = $request->input('val' . $i);
        }
        return view('listing', ['arr' => $arr]);
    }

}


