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
        $user = Auth::user();
        if(is_null(Auth::user()) || $user->username != 'admin') {
            return 'Authentication Error';
        }

        $totalAdds = ['Smartphones' => 0, 'Laptops' => 0, 'Tablets' => 0,];

        $ops = [];
        $ops['Smartphones'] = collect(['IOS', 'Android', 'Windows Phone']);
        $ops['Laptops'] = collect(['MacOS', 'Windows', 'ChromeOS']);
        $ops['Tablets'] = $ops['Smartphones']->concat($ops['Laptops']);

        $ports = collect(['USB-C', 'Micro USB', 'Lightning']);
        $screens = collect(['LCD', 'LED', 'OLED']);

        $bwidth = ['Smartphones' => 6, 'Laptops' => 10, 'Tablets' => 8,];
        
        $res = [];
        $res['Smartphones'] = collect(['800x480', '960x640', '1136x640', '1280x720', '1334x750', '1920x1080', '2560x1440']);
        $res['Laptops'] = collect(['640x360', '1280x720', '1536x864', '1600x900', '1920x1080', '2048x1152', '2560x1440', '3840x2160']);
        $res['Tablets'] = collect(['640x480', '1024x768', '2048x1536', '2732x2048']);

        for($i = 0; $i < $request->lineAmount; $i++) {
            $vendor = User::where('type', 'vendor')->get()->random()->id;
            $type = collect(['Smartphones', 'Laptops', 'Tablets'])->random();

            $year = rand(2008, 2016);
            do {
                $line = ucwords($this->minLengthWord(3));
            } while(Product::where('name', $line . ' 1')->count() != 0);

            $price = rand(50, 250);
            if($type == 'Laptops') {
                $price = $price * 2;
            }
            if($type == 'Tablets') {
                $price = floor($price * 1.5);
            }

            $port = $ports->random();
            $screen = $screens->random();
            $os = $ops[$type]->random();
            $bandwidth = $bwidth[$type];

            $manufacturers = Feature::where('name', 'Manufacturer')->select('value')->distinct()->get();
            $manufacturers = $manufacturers->pluck('value')->all();
            $index = rand(0, count($manufacturers));
            if($index == count($manufacturers)) {
                $manufacturer = fake()->company();
            } else {
                $manufacturer = $manufacturers[$index];
            }

            $bools = [];
            $bools['Bluetooth'] = false;
            $bools['Aux Port'] = false;
            if(rand(1, 10) > 3) {
                $bools['Aux Port'] = true;
            }
            if($type == 'Laptops') {
                $bools['HDMI Port'] = false;
                $bools['DVD Drive'] = false;
                if(rand(1, 10) > 5) {
                    $bools['HDMI Port'] = true;
                }
                if(rand(1, 10) > 4) {
                    $bools['DVD Drive'] = true;
                }
            }

            $twos = [];
            $twos['Memory (GB)'] = collect(['0.5', '1.0', '2.0'])->random();
            $twos['Storage (GB)'] = collect(['32', '64', '128'])->random();
            if($type == 'Laptops') {
                foreach($twos as $two) {
                    $two *= 2;
                }
            }

            if($type == 'Laptops') {
                $usb = rand(0, 3);
            }

            $typeRes = $res[$type];
            $resIndex = rand(0, 1);
            if($typeRes->count() > 5) {
                $resIndex += rand(0, 1);
            }

            $multiplies = [];
            if($type == 'Smartphones') {
                $multiplies['Weight (g)'] = rand(125, 250);
                $multiplies['Battery Life (hr)'] = rand(40, 240) / 10;
                $multiplies['Screen Size (in)'] = rand(45, 85) / 10;
            } else if($type == 'Laptops') {
                $multiplies['Weight (g)'] = rand(1250, 4500);
                $multiplies['Battery Life (hr)'] = rand(50, 360) / 10;
                $multiplies['Screen Size (in)'] = rand(75, 180) / 10;
            } else {
                $multiplies['Weight (g)'] = rand(450, 1250);
                $multiplies['Battery Life (hr)'] = rand(40, 300) / 10;
                $multiplies['Screen Size (in)'] = rand(75, 125) / 10;
            }

            $maxLineProducts = rand(3, 8);
            for($j = 1; $j <= $maxLineProducts; $j++) {
                if($year > 2022) {
                    break;
                }
                $fields = [];
                $fields[] = ['ftr', 'Operating System', $os];
                $fields[] = ['ftr', 'Charging Port', $port];
                $fields[] = ['ftr', 'Screen Type', $screen];
                $fields[] = ['ftr', 'Manufacturer', $manufacturer];

                $fields[] = ['atr', 'Release Year', $year];
                $year += min(rand(1, 3), rand(1, 3));

                $fields[] = ['atr', 'Bandwidth Used (MB)', $bandwidth];

                foreach($bools as $name => $value) {
                    if($value) {
                        $fields[] = ['ftr', $name, 'Yes'];
                        if(rand(1, 10) > 7) {
                            $bools[$name] = false;
                        }
                    } else {
                        if(rand(1, 10) > 6) {
                            $bools[$name] = true;
                        }
                    }
                }

                foreach($twos as $name => $value) {
                    $fields[] = ['atr', $name, $value];
                    if(rand(1, 10) > 6) {
                        $twos[$name] *= 2;
                    }
                }
                
                if($type == 'Laptops') {
                    if($usb > 0) {
                        $fields[] = ['atr', 'USB Ports', $usb];
                    }
                    $usb += rand(-1, 1);
                }

                $fields[] = ['ftr', 'Screen Resolution', $typeRes[$resIndex]];
                if(rand(0, $typeRes->count() - 1) > $resIndex) {
                    $resIndex++;
                }
                
                foreach($multiplies as $name => $value) {
                    $fields[] = ['atr', $name, $value];
                    if(floor($value) == $value) {
                        $multiplies[$name] = floor($value * fake()->randomFloat(2, 0.8, 1.2));
                    } else {
                        $multiplies[$name] = floor($value * fake()->randomFloat(2, 0.8, 1.2) * 10) / 10;
                    }
                }
                
                $totalAdds[$type]++;

                $prod = new Product;
                $prod->type = $type;
                $prod->name = $line . ' ' . $j;
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
                $price = floor($price * fake()->randomFloat(2, 1.05, 1.50)) + rand(10, 50);
            }
        }

        $retString = "";
        foreach($totalAdds as $name => $value) {
            $retString = $retString . $value . ' ' . $name . ' added. <br>';
        }
        return $retString;
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
            $ret = fake()->word();
        } while (mb_strlen($ret) < $len);
        return $ret;
    }
}

