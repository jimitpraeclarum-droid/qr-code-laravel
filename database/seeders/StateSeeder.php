<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            // India (country_id should match ID in countries table)
            ['state_name' => 'Andhra Pradesh', 'country_id' => 101, 'state_alpha_code' => 'AP'],
            ['state_name' => 'Arunachal Pradesh', 'country_id' => 101, 'state_alpha_code' => 'AR'],
            ['state_name' => 'Assam', 'country_id' => 101, 'state_alpha_code' => 'AS'],
            ['state_name' => 'Bihar', 'country_id' => 101, 'state_alpha_code' => 'BR'],
            ['state_name' => 'Chhattisgarh', 'country_id' => 101, 'state_alpha_code' => 'CG'],
            ['state_name' => 'Goa', 'country_id' => 101, 'state_alpha_code' => 'GA'],
            ['state_name' => 'Gujarat', 'country_id' => 101, 'state_alpha_code' => 'GJ'],
            ['state_name' => 'Haryana', 'country_id' => 101, 'state_alpha_code' => 'HR'],
            ['state_name' => 'Himachal Pradesh', 'country_id' => 101, 'state_alpha_code' => 'HP'],
            ['state_name' => 'Jharkhand', 'country_id' => 101, 'state_alpha_code' => 'JH'],
            ['state_name' => 'Karnataka', 'country_id' => 101, 'state_alpha_code' => 'KA'],
            ['state_name' => 'Kerala', 'country_id' => 101, 'state_alpha_code' => 'KL'],
            ['state_name' => 'Madhya Pradesh', 'country_id' => 101, 'state_alpha_code' => 'MP'],
            ['state_name' => 'Maharashtra', 'country_id' => 101, 'state_alpha_code' => 'MH'],
            ['state_name' => 'Manipur', 'country_id' => 101, 'state_alpha_code' => 'MN'],
            ['state_name' => 'Meghalaya', 'country_id' => 101, 'state_alpha_code' => 'ML'],
            ['state_name' => 'Mizoram', 'country_id' => 101, 'state_alpha_code' => 'MZ'],
            ['state_name' => 'Nagaland', 'country_id' => 101, 'state_alpha_code' => 'NL'],
            ['state_name' => 'Odisha', 'country_id' => 101, 'state_alpha_code' => 'OR'],
            ['state_name' => 'Punjab', 'country_id' => 101, 'state_alpha_code' => 'PB'],
            ['state_name' => 'Rajasthan', 'country_id' => 101, 'state_alpha_code' => 'RJ'],
            ['state_name' => 'Sikkim', 'country_id' => 101, 'state_alpha_code' => 'SK'],
            ['state_name' => 'Tamil Nadu', 'country_id' => 101, 'state_alpha_code' => 'TN'],
            ['state_name' => 'Telangana', 'country_id' => 101, 'state_alpha_code' => 'TG'],
            ['state_name' => 'Tripura', 'country_id' => 101, 'state_alpha_code' => 'TR'],
            ['state_name' => 'Uttar Pradesh', 'country_id' => 101, 'state_alpha_code' => 'UP'],
            ['state_name' => 'Uttarakhand', 'country_id' => 101, 'state_alpha_code' => 'UK'],
            ['state_name' => 'West Bengal', 'country_id' => 101, 'state_alpha_code' => 'WB'],
            ['state_name' => 'Delhi', 'country_id' => 101, 'state_alpha_code' => 'DL'],

            // United States (assuming country_id = 233 for USA)
            ['state_name' => 'California', 'country_id' => 233, 'state_alpha_code' => 'CA'],
            ['state_name' => 'Texas', 'country_id' => 233, 'state_alpha_code' => 'TX'],
            ['state_name' => 'Florida', 'country_id' => 233, 'state_alpha_code' => 'FL'],
            ['state_name' => 'New York', 'country_id' => 233, 'state_alpha_code' => 'NY'],
            ['state_name' => 'Illinois', 'country_id' => 233, 'state_alpha_code' => 'IL'],
        ];

        // Clear existing states (optional)
        //DB::table('states')->truncate();

        // Insert states
        DB::table('states')->insert($states);
    }
}
