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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class Datagen extends Controller {

    public function genAuthors(Request $request) {
        $user = Auth::user();
        if(is_null(Auth::user()) || $user->username != 'admin') {
            return 'Authentication Error';
        }

        $genres = [
            'Romance', 'Mystery', 'Science Fiction', 'Adventure', 'Action',
            'Historical Fiction', 'Fantasy', 'Young Adult', 'Mythology',
            'Dystopian', 'Horror', 'Autobiography', 'Biography', 'Self-help',
            'History', 'Travel', 'Computer Science', 'Physics', 'Chemistry',
            'Biology', 'Mathematics', 'Geography', 'Geology', 'Philosophy',
            'Theology', 'Law', 'Education', 'Encyclopedia', 'Cooking', 'Art'
        ];

        $totalBooks = 0;
        for($i = 0; $i < $request->authorAmount; $i++) {
            $author = fake()->name();
            $authorGenres = [];
            foreach(array_rand($genres, rand(2, 6)) as $index) {
                $authorGenres[] = $genres[$index];
            }

            $finalYear = rand(1800, 2010);
            $startYear = $finalYear - rand(10, 60);

            $amount = rand(1, 15);
            for($j = 0; $j < $amount; $j++) {
                $genre = $authorGenres[array_rand($authorGenres)];
                $vendor = User::where('type', 'vendor')->get()->random()->id;
                if(rand(1, 100) > 85) {
                    $seriesAmount = rand(2, 12);
                    $seriesTitle = ucwords(fake()->words(rand(1, 5), true));
                    $runningYear = rand($startYear, ($startYear + $finalYear) / 2);
                    for($k = 1; $k <= $seriesAmount; $k++) {
                        $this->addBook($vendor, $author, $genre, $runningYear, $seriesTitle, $k);
                        $runningYear += rand(0, 5);
                        $totalBooks++;
                    }
                } else {
                    $this->addBook($vendor, $author, $genre, rand($startYear, $finalYear));
                    $totalBooks++;
                }
            }
        }

        return $totalBooks . ' books added.';
    }

    public function genTech(Request $request) {
        //type -- phone, laptop, tablet
        //operating system -- different possibilities depending on type
        //screen size -- type dependent
        //camera quality -- main/front, only for phone/tablet
        //battery life -- type dependent
        //storage capacity -- type dependent
        //memory -- type dependent
        //weight -- type dependent
        //bandwidth used
        //year released
        //name
        //bluetooth, yes/no
        //aux port, yes/no
        //hdmi port, yes/no for laptops
        //usb ports, 0+ for laptops
        //dvd drive, yes/no for laptops
        //webcam quality, for laptops
        //charging port


        //work in product lines
        //  select a type 
        //  select os
        //  select start year, each installment increase somewhat
        //  select initial numeric values, each installment increase or leave
        //  select initial manufacturer, each installment keep or reselect
        //  select product-line name, each installment has number suffixed to it
        //  select initial opt-features, like ports, each installment can change or leave each
    }

    public function genHomes(Request $request) {
        $user = Auth::user();
        if(is_null(Auth::user()) || $user->username != 'admin') {
            return 'Authentication Error';
        }

        $totalRentals = [];
        $totalRentals['Houses'] = 0;
        $totalRentals['Apartments'] = 0;
        $totalForSale = [];
        $totalForSale['Houses'] = 0;
        $totalForSale['Apartments'] = 0;

        for($i = 0; $i < $request->cityAmount; $i++) {
            $vendor = User::where('type', 'vendor')->get()->random()->id;

            $cityYear = rand(1900, 2010);
            $cityPrice = fake()->randomFloat(2, 0.5, 2.15);
            $city = fake()->city();
            $state = fake()->state();
            $cityHomes = rand(15, 45);

            for($j = 0; $j < $cityHomes; $j++) {

                $rental = fake()->boolean();
                $building = collect(['Houses', 'Apartments'])->random();
                $fields = [];

                $fields[] = ['ftr', 'City', $city];
                $fields[] = ['ftr', 'State', $state];
                $fields[] = ['atr', 'Year Built', rand($cityYear, 2020)];

                $address = fake()->buildingNumber() . ' ' . fake()->streetName();
                if($building == 'Apartments') {
                    $address = $address . ' ' . fake()->secondaryAddress();
                } 
                
                $lb = 450;
                $avg = 650;
                $ub = 2000;
                if($building == 'Houses') {
                    $lb = 1500;
                    $avg = 2250;
                    $ub = 5250;
                }
                $val = rand(-50, 50) / 50;
                if($val <= 0) {
                    $val = floor($avg + ($val * ($avg - $lb)));
                } else {
                    $val = floor($avg + ($val * ($ub - $avg)));
                }

                $fields[] = ['atr', 'Square Feet', $val];

                $bed = 1 + rand(0, ($val - 400) / 450);
                $bath = rand(1, $bed) + max(0, rand(0, ($val - 750) / 550));
                $fields[] = ['atr', 'Bedrooms', $bed];
                $fields[] = ['atr', 'Bathrooms', $bath];

                $price = 5 * floor(($val * fake()->randomFloat(2, 0.75, 1.5)) / 5);

                if(rand(1, 10) > 7) {
                    $fields[] = ['ftr', 'Neighborhood Pool', 'Yes'];
                    $price += 5 * rand(10, 50);
                }
                if(rand(1, 10) > 7) {
                    $fields[] = ['ftr', 'HOA', 'Yes'];
                    $price += 5 * rand(5, 25);
                }
                if(rand(1, 10) > 9) {
                    $fields[] = ['ftr', 'Gated Community', 'Yes'];
                    $price += 5 * rand(20, 150);
                }
                if($building == 'Houses') {
                    $acre = fake()->randomFloat(2, 0.2, 1.2) + max(0, rand(-30, 10)) + max(0, rand(-30, 10));
                    $fields[] = ['atr', 'Acreage', $acre];
                    $price += 5 * floor($acre * rand(55, 150) / 5);
                } else {
                    if(rand(1, $val) > 450) {
                        $fields[] = ['ftr', 'In-Unit Laundry', 'Yes'];
                        $price += 5 * rand(5, 25);
                    }
                    if(rand(1, $val) > 750) {
                        $fields[] = ['ftr', 'Kitchen', 'Yes'];
                        $price += 5 * rand(5, 25);
                    }
                }
                
                $price *= $cityPrice;
                $price = 5 * floor($price / 5);

                if(!$rental) {
                    $price *= 100;
                    $assessed = 1000 * floor($price * fake()->randomFloat(2, 0.70, 1.03) / 1000);
                    $fields[] = ['atr', 'Assessed Value', $assessed];
                }


                $prod = new Product;
                if($rental) {
                    $prod->type = 'Rental ' . $building;
                    $totalRentals[$building]++;
                } else {
                    $prod->type = $building;
                    $totalForSale[$building]++;
                }
                $prod->name = $address;
                $prod->save();

                $sells = new Sell;
                $sells->vendor_id = $vendor;
                $sells->product_id = $prod->id;
                $sells->price = $price;
                $sells->save();

                foreach($fields as $rawField) {
                    $field;
                    if($rawField[0] == 'ftr') {
                        $field = new Feature;
                    } else {
                        $field = new Attribute;
                    }
                    $field->name = $rawField[1];
                    $field->value = $rawField[2];
                    $field->product_id = $prod->id;
                    $field->save();
                }

            }
        }

        $retString = "";
        foreach($totalRentals as $type => $num) {
            $retString = $retString . $num . ' ' . $type . ' for rent added. <br>';
        }
        foreach($totalForSale as $type => $num) {
            $retString = $retString . $num . ' ' . $type . ' for sale added. <br>';
        }
        return $retString;

    }

    private function addBook($vendor, $author, $genre, $year, $seriesTitle = null, $seriesIndex = null) {
        $title = ucwords(fake()->words(rand(1, 5), true));
        $pages = rand(75, 1750);


        $prod = new Product;
        $prod->type = 'Books';
        $prod->name = $title;
        $prod->save();

        $sells = new Sell;
        $sells->vendor_id = $vendor;
        $sells->product_id = $prod->id;
        $sells->price = rand(3, 300) + (rand(0, 20) * 0.05);
        $sells->save();

        $fields = [];

        $fields[] = ['ftr', 'Author', $author];
        $fields[] = ['ftr', 'Genre', $genre];
        $fields[] = ['atr', 'Publication Year', $year];
        $fields[] = ['atr', 'Pages', $pages];
        if(isset($seriesIndex)) {
            $fields[] = ['ftr', 'Series', $seriesTitle];
            $fields[] = ['atr', 'Series Order', $seriesIndex];
        }

        foreach($fields as $rawField) {
            $field;
            if($rawField[0] == 'ftr') {
                $field = new Feature;
            } else {
                $field = new Attribute;
            }
            $field->name = $rawField[1];
            $field->value = $rawField[2];
            $field->product_id = $prod->id;
            $field->save();
        }


    }

    private function minLengthWord($len) {
        $ret;
        do {
            $ret = $fake()->word();
        } while (mb_strlen($ret) < $len);
        return ret;
    }
}

