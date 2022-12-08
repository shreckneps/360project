<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Feature;
use App\Models\Needmap;
use App\Models\NeedmapField;
use App\Models\Own;
use App\Models\Product;
use App\Models\Sell;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class Ajax extends Controller {

    public function addListing(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return 'Authentication error.';
        }

        $prod = Product::find($request->product_id);
        if(is_null($prod)) {
            return 'Attempted to add invalid product. Try again or manually specify product details.';
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
                return 'You cannot list a product you already sell.';
            }
            $sell = new Sell;
            $sell->vendor_id = $user->id;
            $sell->product_id = $prod->id;
            $sell->price = $request->sprice;
            $sell->save();
        }
        
        return 'Product added.';
    }

    public function addProduct(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return 'Authentication error.';
        }
        $prod = new Product;
        $prod->name = $request->pname;
        $prod->type = $request->ptype;
        $prod->save();

        $duplicateFilter = [];

        $attributes = Attribute::whereExists(function ($query) use ($request) {
            $query->select(DB::raw(1))
                  ->from('products')
                  ->whereColumn('attributes.product_id', 'products.id')
                  ->where('products.type', $request->ptype);
        });
        $attributes = $attributes->select('name')->distinct()->get()->pluck('name');


        $features = Feature::whereExists(function ($query) use ($request) {
            $query->select(DB::raw(1))
                  ->from('products')
                  ->whereColumn('features.product_id', 'products.id')
                  ->where('products.type', $request->ptype);
        });
        $features = $features->select('name')->distinct()->get()->pluck('name');

        for($i = 0; $i < $request->numAtr; $i++) {
            if($request->filled('atr' . $i)) {
                if(array_key_exists($request->input('atr' . $i), $duplicateFilter)) {
                    continue;
                }
                if($request->input('atr' . $i) == 'Price') {
                    continue;
                }
                if($features->contains($request->input('atr' . $i))) {
                    continue;
                }
                $atr = new Attribute;
                $atr->product_id = $prod->id;
                $atr->name = $request->input('atr' . $i);
                $atr->value = $request->input('atrval' . $i);
                $atr->save();

                $duplicateFilter[$atr->name] = true;
            }
        }

        for($i = 0; $i < $request->numFtr; $i++) {
            if($request->filled('ftr' . $i)) {
                if(array_key_exists($request->input('ftr' . $i), $duplicateFilter)) {
                    continue;
                }
                if($request->input('ftr' . $i) == 'Price') {
                    continue;
                }
                if($attributes->contains($request->input('ftr' . $i))) {
                    continue;
                }
                $ftr = new Feature;
                $ftr->product_id = $prod->id;
                $ftr->name = $request->input('ftr' . $i);
                if($request->filled('ftrval' . $i)) {
                    $ftr->value = $request->input('ftrval' . $i);
                } else {
                    $ftr->value = 'Yes';
                }
                $ftr->save();

                $duplicateFilter[$ftr->name] = true;
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
        
        return 'Product added.';
    }

    public function listExisting(Request $request) {
        $results = Product::where('type', $request->type)
                          ->where('name', $request->name);
        if($results->count() > 0) {
            return '<br><h3>Or Choose an Existing Product: </h3>' . view('list', ['products' => $results->get(), 'adds' => 'yes', 'page' => 1]);
        } else {
            return '';
        }
    }

    public function removeListing(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return 'Authentication error.';
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
        Attribute::where('product_id', $request->del_id)->delete();
        Feature::where('product_id', $request->del_id)->delete();
        Product::destroy($request->del_id);
    }

    public function exactSearch(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return 'Authentication error.';
        }

        $results = Sell
            ::join('products', 'sells.product_id', '=', 'products.id')
            ->where('type', $request->ptype);

        if($request->filled('pname')) {
            $results = $results->where('name', $request->pname);
        }

        for($i = 0; $i < $request->numFld; $i++) {
            if($request->filled('fld' . $i . 'type')) {
                $name = $request->input('fld' . $i);
                $val = $request->input('fld' . $i . 'val');
                $type = $request->input('fld' . $i . 'type');
                $table;

                if($type == 'atr') {
                    $table = 'attributes';
                } else {
                    $table = 'features';
                    if(is_null($val)) {
                        $val = 'Yes';
                    }
                }
                
                $results = $results->whereExists(function ($query) use ($name, $val, $table) {
                    $query->select(DB::raw(1))
                          ->from($table)
                          ->whereColumn($table . '.product_id', 'sells.product_id')
                          ->where($table . '.name', $name)
                          ->where($table . '.value', $val);
                });

            }
        }

        return view('paginatedList', ['products' => $results->get()->sortBy('price'), 'user' => $user, 'page' => $request->input('page', 1)]);
    }

    public function rankedSearch(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return 'Authentication error.';
        }

        $results = Sell
            ::join('products', 'sells.product_id', '=', 'products.id')
            ->where('type', $request->ptype)
            ->get();

        foreach ($results as $key => $result) {
            $result->searchMatch = 0;
            //var_dump($result);
            //foreach($result as $rkey => $rval) {
                //echo $rkey . ' - ' . $rval . ' ';
            //}
        }

        for($i = 0; $i < $request->numFld; $i++) {
            if($request->filled('fld' . $i . 'type')) {
                $name = $request->input('fld' . $i);
                $opr = $this->translateOp($request->input('fld' . $i . 'opr'));
                $val = $request->input('fld' . $i . 'val');
                $type = $request->input('fld' . $i . 'type');
                $weight = $request->input('fld' . $i . 'weight');

                $matching;

                if($name == 'Price') {
                    $matching = $results->where('price', $opr, $val);
                } else {
                    $table;
                    if($type == 'atr') {
                        $table = 'attributes';
                    } else {
                        $table = 'features';
                        if(is_null($val)) {
                            $val = 'Yes';
                        }
                    }
                    $product_ids = DB::table($table)
                                     ->where('name', $name)
                                     ->where('value', $opr, $val)
                                     ->select('product_id')
                                     ->get()
                                     ->pluck('product_id')
                                     ->all();
                    
                    $matching = $results->whereIn('product_id', $product_ids);
                }
                
                if(isset($matching)) {
                    foreach($matching as $match) {
                        $match->searchMatch += $weight;
                    }
                }
            }
        }

/*
        for($i = 0; $i < $request->numFld; $i++) {
            if($request->filled('fld' . $i . 'type')) {
                $name = $request->input('fld' . $i);
                $opr = $request->input('fld' . $i . 'opr');
                $val = $request->input('fld' . $i . 'val');
                $type = $request->input('fld' . $i . 'type');
                $weight = $request->input('fld' . $i . 'weight');
                $savedval;

                if($name == 'Price') {
                    $savedval = $result->price;
                } else { 
                    $table;
                    if($type == 'atr') {
                        $table = 'attributes';
                    } else {
                        $table = 'features';
                        if(is_null($val)) {
                            $val = 'Yes';
                        }
                    }
                    $record = DB::table($table)
                                ->where('product_id', $result->product_id)
                                ->where('name', $name)
                                ->first();

                    if(isset($record)) {
                        $savedval = $record->value;
                    }
                }

                if (isset($savedval) && $this->compareEval($savedval, $opr, $val)) {
                    $result->searchMatch += $weight;
                }

            }
        }
 */           
        $results = $results->sortBy([ ['searchMatch', 'desc'], ['price', 'asc'] ]);

        return view('paginatedList', ['products' => $results, 'user' => $user, 'page' => $request->input('page', 1)]);

    }

    private function translateOp($op) {
        switch($op) {
            case 'eq':
                return '=';
                break;
            case 'ne':
                return '!=';
                break;
            case 'lt':
                return '<';
                break;
            case 'gt':
                return '>';
                break;
            default:
                return '==';
                break;
        }
    }

    public function needSearch(Request $request) {
        $user = Auth::user();
        if(is_null($user) || $user->type != 'customer') {
            return 'Authentication error.';
        }

        $needmap = Needmap::find($request->needmap);
        $sum = 0;
        $lists = collect([]);

        $lhs = NeedmapField::where('needmap_id', $needmap->id)
                           ->where('type', 'lhs')
                           ->get();
        foreach($lhs as $field) {
            $owned = Own::join('products', 'owns.product_id', '=', 'products.id')
                        ->where('customer_id', $user->id);
            if($field->product_type != null) {
                $owned = $owned->where('type', $field->product_type);
            }

            $owned = $owned->get();
            foreach($owned as $item) {
                $fieldValue = Attribute::where('product_id', $item->product_id)
                                       ->firstWhere('name', $field->attribute_name);
                if($fieldValue != null) {
                    $sum += $fieldValue->value;
                }
            }
        }

        $rhs = NeedmapField::where('needmap_id', $needmap->id)
                           ->where('type', 'rhs')
                           ->get();
        foreach($rhs as $field) {
            $existing = Own::join('products', 'owns.product_id', '=', 'products.id')
                           ->where('customer_id', $user->id);
            if($field->product_type != null) {
                $existing = $existing->where('type', $field->product_type);
            }
            $existing = $existing->whereExists(function ($query) use ($field, $sum) {
                $query->select(DB::raw(1))
                      ->from('attributes')
                      ->whereColumn('attributes.product_id', 'owns.product_id')
                      ->where('attributes.name', $field->attribute_name)
                      ->where('attributes.value', '>=', $sum);
            });
            if($existing->count() > 0) {
                continue;
            }

            $sold = Sell::join('products', 'sells.product_id', '=', 'products.id');
            if($field->product_type != null) {
                $sold = $sold->where('type', $field->product_type);
            }
            $sold = $sold->whereExists(function ($query) use ($field, $sum) {
                $query->select(DB::raw(1))
                      ->from('attributes')
                      ->whereColumn('attributes.product_id', 'sells.product_id')
                      ->where('attributes.name', $field->attribute_name)
                      ->where('attributes.value', '>=', $sum);
            });
            
            if($field->product_type != null) {
                $keystring = $field->product_type . ' with sufficient ' . $field->attribute_name;
            } else {
                $keystring = 'Products with sufficient ' . $field->attribute_name;
            }
            $lists[$keystring] = $sold->get();
        }
        
        $pages = collect([]);
        for($i = 0; $i < $lists->count(); $i++) {
            $pages[$i] = $request->input('page' . $i, 1);
        }
        return view('needList', ['sum' => $sum, 'lists' => $lists, 'pages' => $pages]);
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

    public function getForm(Request $request, $type) {
        if (View::exists('form.' . $type)) {
            return view('form.' . $type, ['num' => $request->num, 'side' => $request->side]);
        } else {
            return 'Error finding desired form.';
        }
    }

    public function getFormCmpr(Request $request) {
        $sum = $request->numAtr + $request->numFtr;
        if($request->type == 'atr') {
            return view('form.attributeComparison', ['num' => $request->numAtr, 'sum' => $sum]);
        } else {
            return view('form.featureComparison', ['num' => $request->numFtr, 'sum' => $sum]);
        }
    }

    public function addNeedmap(Request $request) {
        $user = Auth::user();
        if(is_null($user) || $user->username != 'admin') {
            return redirect('/');
        }

        $map = new Needmap;
        $map->name = $request->mname;
        $map->description = $request->mdesc;
        $map->save();

        for($i = 0; $i < $request->numLhs; $i++) {
            if(! $request->filled('lhs' . $i . 'name')) {
                continue;
            }
            $field = new NeedmapField;
            $field->needmap_id = $map->id;
            if($request->filled('lhs' . $i . 'type')) {
                $field->product_type = $request->input('lhs' . $i . 'type');
            }
            $field->attribute_name = $request->input('lhs' . $i . 'name');
            $field->type = 'lhs';
            $field->save();
        }

        for($i = 0; $i < $request->numRhs; $i++) {
            if(! $request->filled('rhs' . $i . 'name')) {
                continue;
            }
            $field = new NeedmapField;
            $field->needmap_id = $map->id;
            if($request->filled('rhs' . $i . 'type')) {
                $field->product_type = $request->input('rhs' . $i . 'type');
            }
            $field->attribute_name = $request->input('rhs' . $i . 'name');
            $field->type = 'rhs';
            $field->save();
        }

        return 'Needmap added';
    }

    public function userProducts(Request $request) {
        $user = Auth::user();
        if(is_null($user)) {
            return 'Authentication error.';
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

        return view('paginatedList', ['products' => $results, 'user' => $user, 'deletes' => true, 'page' => $request->input('page', 1)]);
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


