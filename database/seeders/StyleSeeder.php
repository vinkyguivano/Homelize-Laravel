<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //10
        DB::table('styles')->insert([
            [
                'name' => 'Minimalis'
            ],
            [
                'name' => 'Modern',
            ],
            [
                'name' => 'Klasik',
            ],
            [
                'name' => 'Kontemporer'
            ],
            [
                'name' => 'Industrial'
            ],
            [
                'name' => 'Rustic'
            ],
            [
                'name' => 'Skandinavian'
            ],
            [
                'name' => 'Mediteranian'
            ],
            [
                'name' => 'Mid Century'
            ],
            [
                'name' => 'Tradisional'
            ]
        ]);
    }
}
