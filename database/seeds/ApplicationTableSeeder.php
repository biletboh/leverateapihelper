<?php

use Illuminate\Database\Seeder;

class ApplicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('application')->insert([
            'name' => 'CredoTrade',
            'key' => '1234asdf',
            'secret' => 'secret',
        ]);
    }
}
