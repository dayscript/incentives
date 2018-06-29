<?php

use Illuminate\Database\Seeder;

class States extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            'name' => "Activo"
        ]);
        DB::table('states')->insert([
            'name' => "Inactivo"
        ]);
    }
}
