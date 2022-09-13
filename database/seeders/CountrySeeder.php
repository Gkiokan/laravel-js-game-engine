<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Http\Controllers\WCX\CountryController;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = (new CountryController)->getCountries();
        $prioCountries = ['DE', 'USA', 'EN', 'ES', 'IT', 'FR', 'NO', 'JP', 'RU', 'CN', 'PL', 'SE', 'FI', 'DK'];

        Country::truncate();

        foreach($countries as $country):
            $prio = in_array($country['alpha_2_code'], $prioCountries) ? 1 : 0;

            Country::updateOrCreate([
                'name'    => $country['nationality'],
                'alpha2'  => $country['alpha_2_code'],
                'alpha3'  => $country['alpha_3_code'],
                'prio'    => $prio,
            ]);
        endforeach;
    }
}
