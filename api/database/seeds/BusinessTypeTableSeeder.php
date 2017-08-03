<?php

use Illuminate\Database\Seeder;

class BusinessTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB:: table('business_types') -> insert(array(
          array("id" => "1","title" => "KTV", "description" => "KTV Bar"),
          array("id" => "2","title" => "School", "description" => "Universities and Public Schools"),
          array("id" => "3","title" => "Shippings", "description" => "Shipping Lines")
        ));
    }
}
