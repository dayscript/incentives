<?php

use Illuminate\Database\Seeder;

class Type extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
            'name' => "Goal"
        ]);
        DB::table('types')->insert([
            'name' => "Rule"
        ]);
    }
}
