<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB:: table('companies') -> insert(array(
          array(
            "id"    => "1",
            "business_type_id" => "1",
            "name"  => "K1",
            "address"   => "Crossroads",
            "contact_number"    => "123456789",
            "email" => "k1@gmail.com",
            "lat"   => "123.1234556",
            "lng"   => "5.1234556"
          ),
        ));
    }
}
