<?php

use Illuminate\Database\Seeder;

class Novopayment1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'name' => "NOVOPAYMENT",
            'image' => "",
            'nit' => "123456",
            'address' => "ertyuio"
        ]);
        DB::table('parameters')->insert([
            'name' => "ambiente",
            'valor' => "pruebas",
            'client_id' => 1
        ]);
        DB::table('parameters')->insert([
            'name' => "AGILE_DOMAIN",
            'valor' => "novopayment",
            'client_id' => 1
        ]);
        DB::table('parameters')->insert([
            'name' => "AGILE_USER_EMAIL",
            'valor' => "info@millaextra.co",
            'client_id' => 1
        ]);
        DB::table('parameters')->insert([
            'name' => "AGILE_REST_API_KEY",
            'valor' => "79kuaj9kdfrrdafh61kagcrlll",
            'client_id' => 1
        ]);
        DB::table('parameters')->insert([
            'name' => "AGILE_JS_API_KEY",
            'valor' => "1t31vbv0vve0njpecq8igh4361",
            'client_id' => 1
        ]);
    }
}
